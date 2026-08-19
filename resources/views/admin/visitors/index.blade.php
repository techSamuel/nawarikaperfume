@extends('admin.layouts.app')
@section('title', 'Visitor Logs')
@section('page_title', 'Visitor Stayed Time & Grouped Analytics')

@section('header_actions')
    @if($groups->count() > 0)
        <form action="{{ route('admin.visitors.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear ALL visitor log records? This cannot be undone.');" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-sm" style="background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); font-weight:600;">
                🗑️ Clear All Logs
            </button>
        </form>
    @endif
@endsection

@section('content')
{{-- METRICS SUMMARY CARDS --}}
<div class="metrics-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="admin-card" style="padding: 20px;">
        <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Total Stayed Time</div>
        <div style="font-size: 1.8rem; font-weight: 800; color: #10b981; margin-top: 4px;">⏱️ {{ $metrics['total_stayed_time'] }}</div>
    </div>
    <div class="admin-card" style="padding: 20px;">
        <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Total Pageviews</div>
        <div style="font-size: 1.8rem; font-weight: 800; color: var(--accent-primary); margin-top: 4px;">{{ number_format($metrics['total_visits']) }}</div>
    </div>
    <div class="admin-card" style="padding: 20px;">
        <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Unique Visitors (IPs)</div>
        <div style="font-size: 1.8rem; font-weight: 800; color: #3b82f6; margin-top: 4px;">{{ number_format($metrics['unique_ips']) }}</div>
    </div>
    <div class="admin-card" style="padding: 20px;">
        <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Today's Visits</div>
        <div style="font-size: 1.8rem; font-weight: 800; color: #f59e0b; margin-top: 4px;">{{ number_format($metrics['today_visits']) }}</div>
    </div>
</div>

{{-- TOOLBAR FILTERS --}}
<div class="admin-toolbar" style="margin-bottom: 20px;">
    <div class="admin-toolbar-left" style="display:flex; gap:12px; flex-wrap:wrap; align-items:center;">
        <div class="admin-filters">
            <a href="{{ route('admin.visitors.index') }}" class="filter-btn {{ !request('user_type') ? 'active' : '' }}">All Visitors</a>
            <a href="{{ route('admin.visitors.index', ['user_type' => 'Guest']) }}" class="filter-btn {{ request('user_type') === 'Guest' ? 'active' : '' }}">Guests</a>
            <a href="{{ route('admin.visitors.index', ['user_type' => 'Logged-in User']) }}" class="filter-btn {{ request('user_type') === 'Logged-in User' ? 'active' : '' }}">Users</a>
        </div>
    </div>
    <form action="{{ route('admin.visitors.index') }}" method="GET" style="display:flex; gap:10px;">
        @if(request('user_type'))
            <input type="hidden" name="user_type" value="{{ request('user_type') }}">
        @endif
        <div class="search-bar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search IP, User, Country, OS...">
        </div>
    </form>
</div>

{{-- GROUPED VISITOR TABLE --}}
<div class="admin-card">
    <div class="admin-card-body no-padding">
        @if($groups->count() > 0)
            <table class="admin-table" style="border-collapse: collapse; width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 40px;"></th>
                        <th>IP Address</th>
                        <th>Visitor Identity</th>
                        <th>Device & OS</th>
                        <th>Total Stayed Time</th>
                        <th>Visits Count</th>
                        <th>Country / Timezone</th>
                        <th>Last Active</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groups as $group)
                        <tr style="cursor: pointer; background: var(--bg-card); border-bottom: 1px solid var(--border-color);" onclick="toggleGroupDetails('{{ $group->group_id }}', event)">
                            <td style="text-align: center; font-size: 1rem; color: var(--accent-primary);">
                                <span id="arrow-{{ $group->group_id }}">▶</span>
                            </td>
                            <td>
                                <div style="font-family: monospace; font-size: 0.95rem; font-weight: 700; color: var(--accent-primary);">
                                    {{ $group->ip_address }}
                                </div>
                            </td>
                            <td>
                                <div>
                                    @if($group->user_type === 'Guest')
                                        <span class="badge badge-secondary" style="font-size:0.75rem;">👤 Guest</span>
                                    @else
                                        <span class="badge badge-primary" style="font-size:0.75rem;">🔑 {{ $group->user_type }}</span>
                                        <div style="font-weight:600; margin-top:2px;">{{ $group->user_name }}</div>
                                        <div style="font-size:0.75rem; color:var(--text-muted);">{{ $group->user_email }}</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div style="display:flex; align-items:center; gap:6px;">
                                    <span style="font-size:1.2rem;">{{ $group->device_icon }}</span>
                                    <div>
                                        <div style="font-weight:600; font-size:0.85rem;">{{ $group->device_type }}</div>
                                        <div style="font-size:0.75rem; color:var(--text-muted);">{{ $group->platform }} • {{ $group->browser }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-success" style="font-size:0.85rem; padding: 6px 12px; font-weight: 700; background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3);">
                                    ⏱️ {{ $group->formatted_stayed_time }}
                                </span>
                            </td>
                            <td>
                                <span style="font-weight:700; font-size:0.9rem; color:var(--text-primary);">
                                    {{ $group->total_pages_visited }} {{ Str::plural('page', $group->total_pages_visited) }}
                                </span>
                            </td>
                            <td>
                                <div style="font-size:0.82rem; font-weight:600;">🌐 {{ $group->country }}</div>
                                <div style="font-size:0.75rem; color:var(--text-muted);">🕒 {{ $group->timezone ?? 'Asia/Dhaka' }}</div>
                            </td>
                            <td>
                                <div style="font-size:0.85rem; font-weight:600;">{{ $group->last_visit->format('M d, h:i A') }}</div>
                                <div style="font-size:0.75rem; color:var(--text-muted);">{{ $group->last_visit->diffForHumans() }}</div>
                            </td>
                            <td onclick="event.stopPropagation();">
                                <button type="button" class="btn btn-outline btn-sm" onclick="toggleGroupDetails('{{ $group->group_id }}', event)" style="margin-right: 6px;">
                                    View All ({{ $group->total_pages_visited }})
                                </button>
                            </td>
                        </tr>

                        {{-- EXPANDABLE NESTED DETAILED VISITS ROW --}}
                        <tr id="details-{{ $group->group_id }}" style="display: none; background: var(--bg-secondary);">
                            <td colspan="9" style="padding: 16px 24px; border-bottom: 2px solid var(--accent-primary);">
                                <div style="margin-bottom: 10px; font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); display: flex; justify-content: space-between; align-items: center;">
                                    <span>All Visits History for IP <code>{{ $group->ip_address }}</code> ({{ $group->total_pages_visited }} Pageviews • Total Stayed: {{ $group->formatted_stayed_time }})</span>
                                    <span style="font-size: 0.75rem; color: var(--text-muted);">Click row to close</span>
                                </div>
                                <div style="overflow-x: auto; background: var(--bg-card); border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                                    <table style="width: 100%; border-collapse: collapse; font-size: 0.82rem;">
                                        <thead>
                                            <tr style="background: var(--bg-tertiary); text-align: left; color: var(--text-muted);">
                                                <th style="padding: 8px 12px;">#</th>
                                                <th style="padding: 8px 12px;">Page URL</th>
                                                <th style="padding: 8px 12px;">Stayed Time</th>
                                                <th style="padding: 8px 12px;">Exact Timestamp</th>
                                                <th style="padding: 8px 12px;">Referer</th>
                                                <th style="padding: 8px 12px; text-align: right;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($group->visits as $idx => $visit)
                                                <tr style="border-bottom: 1px solid var(--border-color);">
                                                    <td style="padding: 8px 12px; color: var(--text-muted);">{{ $idx + 1 }}</td>
                                                    <td style="padding: 8px 12px; max-width: 320px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-weight: 600;">
                                                        <a href="{{ $visit->url }}" target="_blank" style="color: var(--accent-primary); text-decoration: none;">
                                                            {{ parse_url($visit->url, PHP_URL_PATH) ?: '/' }}
                                                        </a>
                                                    </td>
                                                    <td style="padding: 8px 12px;">
                                                        <span style="font-weight: 700; color: #10b981;">⏱️ {{ $visit->formatted_duration }}</span>
                                                    </td>
                                                    <td style="padding: 8px 12px; color: var(--text-secondary);">
                                                        {{ $visit->visited_at->format('M d, Y h:i:s A') }}
                                                    </td>
                                                    <td style="padding: 8px 12px; color: var(--text-muted); max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                        {{ $visit->referer ?: 'Direct' }}
                                                    </td>
                                                    <td style="padding: 8px 12px; text-align: right;">
                                                        <form action="{{ route('admin.visitors.destroy', $visit->id) }}" method="POST" onsubmit="return confirm('Delete this visit log?');" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 0.85rem;" title="Delete this visit">
                                                                ✕
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <div class="empty-icon">🌐</div>
                <h3>No visitor logs recorded</h3>
                <p>Visitor visits, stayed times, IP details, and device info will group here live as visitors browse your store.</p>
            </div>
        @endif
    </div>
</div>
<div class="pagination-wrapper">{{ $groups->links() }}</div>

@push('scripts')
<script>
function toggleGroupDetails(groupId, event) {
    const detailsRow = document.getElementById('details-' + groupId);
    const arrow = document.getElementById('arrow-' + groupId);
    
    if (detailsRow) {
        if (detailsRow.style.display === 'none') {
            detailsRow.style.display = 'table-row';
            if (arrow) arrow.innerText = '▼';
        } else {
            detailsRow.style.display = 'none';
            if (arrow) arrow.innerText = '▶';
        }
    }
}
</script>
@endpush
@endsection
