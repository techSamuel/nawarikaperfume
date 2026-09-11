@extends('admin.layouts.app')
@section('title', 'Products')
@section('page_title', 'Products')
@section('header_actions')
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">+ Add Product</a>
@endsection

@section('content')
<div class="admin-toolbar">
    <div class="admin-toolbar-left">
        <form action="{{ route('admin.products.index') }}" method="GET" style="display:flex; gap:8px; flex-wrap:wrap;">
            <div class="search-bar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products...">
            </div>
            <select name="category" class="form-control" style="width:auto; padding:8px 16px;" onchange="this.form.submit()">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="status" class="form-control" style="width:auto; padding:8px 16px;" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </form>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-body no-padding">
        @if($products->count() > 0)
            <table class="admin-table">
                <thead>
                    <tr><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Featured</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>
                                <div class="table-product">
                                    <div class="table-product-image">
                                        @if($product->image)
                                            <img src="{{ asset('uploads/' . $product->image) }}" alt="">
                                        @else
                                            <div class="img-placeholder" style="font-size:1rem;">📦</div>
                                        @endif
                                    </div>
                                    <div>
                                        <div style="font-weight:600;">{{ Str::limit($product->name, 30) }}</div>
                                        <div style="font-size:0.75rem; color:var(--text-muted);">{{ $product->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $product->category->name ?? '—' }}</td>
                            <td>
                                @if($product->isOnSale())
                                    <span style="text-decoration:line-through; color:var(--text-muted); font-size:0.8rem;">৳{{ number_format($product->price, 2) }}</span><br>
                                    <span style="font-weight:600; color:var(--success);">৳{{ number_format($product->sale_price, 2) }}</span>
                                @else
                                    <span style="font-weight:600;">৳{{ number_format($product->price, 2) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($product->stock == 0)
                                    <span class="badge badge-danger">Out</span>
                                @elseif($product->stock <= 5)
                                    <span class="badge badge-warning">{{ $product->stock }}</span>
                                @else
                                    <span class="badge badge-success">{{ $product->stock }}</span>
                                @endif
                            </td>
                            <td>{{ $product->is_featured ? '⭐' : '—' }}</td>
                            <td>
                                <span class="badge {{ $product->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline btn-sm">Edit</a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?')">
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
            <div class="empty-state"><div class="empty-icon">📦</div><h3>No products</h3><p>Add your first product to get started.</p></div>
        @endif
    </div>
</div>
<div class="pagination-wrapper">{{ $products->links() }}</div>
@endsection
