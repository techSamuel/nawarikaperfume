<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisitorLog;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class VisitorLogController extends Controller
{
    public function index(Request $request)
    {
        $query = VisitorLog::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                  ->orWhere('user_name', 'like', "%{$search}%")
                  ->orWhere('user_email', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%")
                  ->orWhere('browser', 'like', "%{$search}%")
                  ->orWhere('platform', 'like', "%{$search}%")
                  ->orWhere('url', 'like', "%{$search}%");
            });
        }

        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);
        }

        if ($request->filled('device_type')) {
            $query->where('device_type', $request->device_type);
        }

        $allLogs = $query->latest('visited_at')->get();

        // Group by IP address + Device Type + User
        $grouped = $allLogs->groupBy(function ($log) {
            return $log->ip_address . '___' . ($log->device_type ?? 'Desktop') . '___' . ($log->user_id ?? 'guest');
        });

        $groupList = [];
        foreach ($grouped as $key => $logs) {
            $first = $logs->first();
            $totalSeconds = $logs->sum('duration_seconds');
            
            $groupList[] = (object)[
                'group_id' => md5($key),
                'ip_address' => $first->ip_address,
                'user_type' => $first->user_type,
                'user_name' => $first->user_name,
                'user_email' => $first->user_email,
                'device_type' => $first->device_type,
                'device_icon' => $first->device_icon,
                'platform' => $first->platform,
                'browser' => $first->browser,
                'country' => $first->country,
                'timezone' => $first->timezone,
                'total_stayed_seconds' => $totalSeconds,
                'formatted_stayed_time' => $this->formatDuration($totalSeconds),
                'total_pages_visited' => $logs->count(),
                'first_visit' => $logs->min('visited_at'),
                'last_visit' => $logs->max('visited_at'),
                'visits' => $logs,
            ];
        }

        // Sort groups by latest visit
        usort($groupList, fn($a, $b) => $b->last_visit <=> $a->last_visit);

        // Manual Pagination for grouped collection
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;
        $currentItems = array_slice($groupList, ($currentPage - 1) * $perPage, $perPage);
        $paginatedGroups = new LengthAwarePaginator($currentItems, count($groupList), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'query' => $request->query(),
        ]);

        $metrics = [
            'total_visits' => VisitorLog::count(),
            'unique_ips' => VisitorLog::distinct('ip_address')->count('ip_address'),
            'total_stayed_time' => $this->formatDuration(VisitorLog::sum('duration_seconds')),
            'today_visits' => VisitorLog::whereDate('visited_at', today())->count(),
            'guest_visits' => VisitorLog::where('user_type', 'Guest')->count(),
            'user_visits' => VisitorLog::where('user_type', '!=', 'Guest')->count(),
        ];

        return view('admin.visitors.index', [
            'groups' => $paginatedGroups,
            'metrics' => $metrics,
        ]);
    }

    public function destroyGroup(Request $request, string $ip)
    {
        VisitorLog::where('ip_address', $ip)->delete();
        return back()->with('success', 'All visitor records for IP ' . $ip . ' deleted.');
    }

    public function destroy(VisitorLog $visitorLog)
    {
        $visitorLog->delete();
        return back()->with('success', 'Visitor log record deleted.');
    }

    public function clearAll()
    {
        VisitorLog::truncate();
        return back()->with('success', 'All visitor logs have been cleared.');
    }

    private function formatDuration(int $seconds): string
    {
        if ($seconds < 60) {
            return $seconds . 's';
        }
        $minutes = floor($seconds / 60);
        $remSeconds = $seconds % 60;
        if ($minutes < 60) {
            return $minutes . 'm ' . $remSeconds . 's';
        }
        $hours = floor($minutes / 60);
        $remMinutes = $minutes % 60;
        return $hours . 'h ' . $remMinutes . 'm';
    }
}
