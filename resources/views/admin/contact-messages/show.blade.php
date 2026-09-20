@extends('admin.layouts.app')
@section('title', 'View Message')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>View Message</h2>
    <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-secondary">Back to List</a>
</div>

<div class="card">
    <div class="card-body">
        <h4 class="mb-4">{{ $message->subject }}</h4>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <p><strong>From:</strong> {{ $message->name }}</p>
                <p><strong>Email:</strong> <a href="mailto:{{ $message->email }}">{{ $message->email }}</a></p>
            </div>
            <div class="col-md-6 text-md-end">
                <p><strong>Date:</strong> {{ $message->created_at->format('M d, Y h:i A') }}</p>
                @if($message->phone)
                    <p><strong>Phone:</strong> {{ $message->phone }}</p>
                @endif
            </div>
        </div>

        <hr>

        <div class="mt-4 bg-light p-4 rounded">
            {!! nl2br(e($message->message)) !!}
        </div>

        <div class="mt-4">
            <a href="mailto:{{ $message->email }}?subject=Re: {{ urlencode($message->subject) }}" class="btn btn-primary">Reply via Email</a>
            <form action="{{ route('admin.contact-messages.destroy', $message->id) }}" method="POST" class="d-inline-block ms-2" onsubmit="return confirm('Are you sure you want to delete this message?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete Message</button>
            </form>
        </div>
    </div>
</div>
@endsection
