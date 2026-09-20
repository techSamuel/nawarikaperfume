@extends('admin.layouts.app')
@section('title', 'Contact Messages')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Contact Messages</h2>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $msg)
                        <tr class="{{ $msg->is_read ? '' : 'fw-bold bg-light' }}">
                            <td>{{ $msg->created_at->format('M d, Y h:i A') }}</td>
                            <td>
                                {{ $msg->name }}
                                <div class="text-muted small">{{ $msg->email }}</div>
                            </td>
                            <td>{{ Str::limit($msg->subject, 50) }}</td>
                            <td>
                                @if($msg->is_read)
                                    <span class="badge bg-secondary">Read</span>
                                @else
                                    <span class="badge bg-primary">New</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.contact-messages.show', $msg->id) }}" class="btn btn-sm btn-info text-white">View</a>
                                <form action="{{ route('admin.contact-messages.destroy', $msg->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">No contact messages found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $messages->links() }}
</div>
@endsection
