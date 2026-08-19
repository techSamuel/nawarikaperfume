@extends('admin.layouts.app')
@section('title', 'Add Product')
@section('page_title', 'Add Product')
@section('header_actions')
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline btn-sm">← Back</a>
@endsection

@section('content')
    <div class="admin-card" style="max-width:800px;">
        <div class="admin-card-body">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="form-group" style="width: 100%;">
                        <label for="name">Product Name *</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="category_id">Category *</label>
                        <select id="category_id" name="category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group" style="width: 100%;">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control"
                            rows="4">{{ old('description') }}</textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Price (৳) *</label>
                        <input type="number" id="price" name="price" class="form-control" value="{{ old('price') }}"
                            step="0.01" min="0" required>
                        @error('price') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="sale_price">Sale Price (৳)</label>
                        <input type="number" id="sale_price" name="sale_price" class="form-control"
                            value="{{ old('sale_price') }}" step="0.01" min="0">
                        @error('sale_price') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="stock">Stock Quantity *</label>
                        <input type="number" id="stock" name="stock" class="form-control" value="{{ old('stock', 0) }}"
                            min="0" required>
                        @error('stock') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="image">Main Product Image *</label>
                        <input type="file" id="image" name="image" class="form-control" accept="image/*" required>
                        @error('image') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="gallery">Additional Images (Gallery)</label>
                        <input type="file" id="gallery" name="gallery[]" class="form-control" accept="image/*" multiple>
                        <small style="color:var(--text-muted); display:block; margin-top:4px;">You can select multiple
                            images.</small>
                        @error('gallery.*') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="videos">Product Videos (MP4)</label>
                        <input type="file" id="videos" name="videos[]" class="form-control"
                            accept="video/mp4,video/quicktime" multiple>
                        <small style="color:var(--text-muted); display:block; margin-top:4px;">Upload MP4 or MOV
                            files.</small>
                        @error('videos.*') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                            <input type="checkbox" name="is_active" value="1" checked
                                style="accent-color:var(--accent-primary);">
                            Active
                        </label>
                    </div>
                    <div class="form-group">
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                                style="accent-color:var(--accent-primary);">
                            Featured
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Create Product</button>
            </form>
        </div>
    </div>
@endsection