@extends('admin.layouts.app')
@section('title', 'Categories')
@section('page_title', 'Categories')
@section('header_actions')
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">+ Add Category</a>
@endsection

@section('content')
<div class="admin-card">
    <div class="admin-card-body no-padding">
        @if($categories->count() > 0)
            <table class="admin-table">
                <thead>
                    <tr><th>Image</th><th>Name</th><th>Slug</th><th>Products</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>
                                <div class="table-product-image">
                                    @if($category->image)
                                        <img src="{{ asset('uploads/' . $category->image) }}" alt="">
                                    @else
                                        <div class="img-placeholder" style="font-size:1rem;">📂</div>
                                    @endif
                                </div>
                            </td>
                            <td style="font-weight:600;">{{ $category->name }}</td>
                            <td style="color:var(--text-muted);">{{ $category->slug }}</td>
                            <td><span class="badge badge-info">{{ $category->products_count }}</span></td>
                            <td>
                                <span class="badge {{ $category->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline btn-sm">Edit</a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state"><div class="empty-icon">📂</div><h3>No categories</h3><p>Create your first category to organize products.</p></div>
        @endif
    </div>
</div>
<div class="pagination-wrapper">{{ $categories->links() }}</div>
@endsection
