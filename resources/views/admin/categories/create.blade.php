@extends('admin.layouts.app')
@section('title', 'Create Category')
@section('page_title', 'Create Category')
@section('header_actions')
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline btn-sm">← Back</a>
@endsection

@section('content')
<div class="admin-card" style="max-width:700px;">
    <div class="admin-card-body">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group" style="width: 100%;">
                    <label for="name">Category Name *</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                @error('description') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" id="image" name="image" class="form-control" accept="image/*">
                @error('image') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                    <input type="checkbox" name="is_active" value="1" checked style="accent-color:var(--accent-primary);">
                    Active
                </label>
            </div>
            <button type="submit" class="btn btn-primary">Create Category</button>
        </form>
    </div>
</div>
@endsection
