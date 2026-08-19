@extends('admin.layouts.app')
@section('title', 'Edit Category')
@section('page_title', 'Edit Category')
@section('header_actions')
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline btn-sm">← Back</a>
@endsection

@section('content')
<div class="admin-card" style="max-width:700px;">
    <div class="admin-card-body">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="form-row">
                <div class="form-group" style="width: 100%;">
                    <label for="name">Category Name *</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
            </div>
            <div class="form-group">
                <label for="image">Image</label>
                @if($category->image)
                    <div style="margin-bottom:8px;">
                        <img src="{{ asset('storage/' . $category->image) }}" alt="" style="width:120px; height:120px; object-fit:cover; border-radius:var(--radius-md); border:1px solid var(--border-color);">
                    </div>
                @endif
                <input type="file" id="image" name="image" class="form-control" accept="image/*">
                @error('image') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }} style="accent-color:var(--accent-primary);">
                    Active
                </label>
            </div>
            <button type="submit" class="btn btn-primary">Update Category</button>
        </form>
    </div>
</div>
@endsection
