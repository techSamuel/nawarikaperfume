<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount('orders')
            ->withSum('orders as total_spent', 'total');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role') && in_array($request->role, ['customer', 'admin'])) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            if ($request->status === 'banned') {
                $query->where('is_banned', true);
            } elseif ($request->status === 'active') {
                $query->where('is_banned', false);
            }
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        $metrics = [
            'total' => User::count(),
            'customers' => User::where('role', 'customer')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'banned' => User::where('is_banned', true)->count(),
        ];

        return view('admin.users.index', compact('users', 'metrics'));
    }

    public function show(User $user)
    {
        $user->loadCount('orders');
        $orders = $user->orders()->latest()->paginate(10);
        $totalSpent = $user->orders()->where('status', '!=', 'cancelled')->sum('total');
        $completedOrdersCount = $user->orders()->where('status', 'delivered')->count();

        return view('admin.users.show', compact('user', 'orders', 'totalSpent', 'completedOrdersCount'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'in:customer,admin'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'zip' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.show', $user)->with('success', "User {$user->name} updated successfully.");
    }

    public function toggleBan(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot ban your own administrator account.');
        }

        if ($user->isBanned()) {
            $user->unban();
            return back()->with('success', "User {$user->name} has been unbanned successfully.");
        }

        $reason = trim($request->input('ban_reason', 'Account suspended by administrator'));
        $user->ban($reason);

        return back()->with('success', "User {$user->name} has been banned.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', "User {$name} deleted successfully.");
    }
}
