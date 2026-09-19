@extends('admin.layouts.app')
@section('title', 'Edit Product')
@section('page_title', 'Edit Product')
@section('header_actions')
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline btn-sm">← Back</a>
@endsection

@section('content')
    <div class="admin-card" style="max-width:800px;">
        <div class="admin-card-body">
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="form-row">
                    <div class="form-group" style="width: 100%;">
                        <label for="name">Product Name *</label>
                        <input type="text" id="name" name="name" class="form-control"
                            value="{{ old('name', $product->name) }}" required>
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="category_id">Category *</label>
                        <select id="category_id" name="category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group" style="width: 100%;">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control"
                            rows="4">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Price (৳) *</label>
                        <input type="number" id="price" name="price" class="form-control"
                            value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
                        @error('price') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="sale_price">Sale Price (৳)</label>
                        <input type="number" id="sale_price" name="sale_price" class="form-control"
                            value="{{ old('sale_price', $product->sale_price) }}" step="0.01" min="0">
                        @error('sale_price') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="delivery_charge">Individual Delivery Charge (৳)</label>
                        <input type="number" id="delivery_charge" name="delivery_charge" class="form-control"
                            value="{{ old('delivery_charge', $product->delivery_charge) }}" step="0.01" min="0">
                        <small style="color:var(--text-muted); display:block; margin-top:4px;">Leave blank to use the Universal Delivery Charge from settings.</small>
                        @error('delivery_charge') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="stock">Stock Quantity *</label>
                        <input type="number" id="stock" name="stock" class="form-control"
                            value="{{ old('stock', $product->stock) }}" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Main Product Image *</label>
                        @if($product->image)
                            <div style="margin-bottom:8px;">
                                <img src="{{ asset('uploads/' . $product->image) }}" alt=""
                                    style="width:100px; height:100px; object-fit:cover; border-radius:var(--radius-md); border:1px solid var(--border-color);">
                            </div>
                        @endif
                        <input type="file" id="image" name="image" class="form-control" accept="image/*">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="gallery">Additional Images (Gallery)</label>
                        @if($product->gallery && count($product->gallery) > 0)
                            <div style="display: flex; gap: 8px; margin-bottom: 8px; flex-wrap: wrap;">
                                @foreach($product->gallery as $img)
                                    <img src="{{ asset('uploads/' . $img) }}"
                                        style="width:60px; height:60px; object-fit:cover; border-radius:4px; border:1px solid var(--border-color);">
                                @endforeach
                            </div>
                        @endif
                        <input type="file" id="gallery" name="gallery[]" class="form-control" accept="image/*" multiple>
                        <small style="color:var(--text-muted); display:block; margin-top:4px;">Upload multiple images to
                            append to the gallery.</small>
                    </div>
                    <div class="form-group">
                        <label for="videos">Product Videos (MP4)</label>
                        @if($product->videos && count($product->videos) > 0)
                            <div style="display: flex; gap: 8px; margin-bottom: 8px; flex-wrap: wrap;">
                                @foreach($product->videos as $vid)
                                    <video src="{{ asset('uploads/' . $vid) }}"
                                        style="width:100px; height:60px; object-fit:cover; border-radius:4px; border:1px solid var(--border-color);"
                                        controls></video>
                                @endforeach
                            </div>
                        @endif
                        <input type="file" id="videos" name="videos[]" class="form-control"
                            accept="video/mp4,video/quicktime" multiple>
                        <small style="color:var(--text-muted); display:block; margin-top:4px;">Upload MP4 files to append to
                            videos.</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} style="accent-color:var(--accent-primary);">
                            Active
                        </label>
                    </div>
                    <div class="form-group">
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} style="accent-color:var(--accent-primary);">
                            Featured
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="margin-top: 16px;">Update Product</button>
            </form>
        </div>
    </div>

    {{-- PRODUCT REVIEWS SECTION --}}
    <div class="admin-card" style="max-width:800px; margin-top: 32px;">
        <div class="admin-card-header" style="padding: 16px 24px; border-bottom: 1px solid var(--border-color);">
            <h3 style="margin: 0;">Customer Reviews (Editable)</h3>
        </div>
        <div class="admin-card-body">
            {{-- Add New Review Form --}}
            <div style="background: var(--bg-tertiary); padding: 16px; border-radius: 8px; margin-bottom: 24px;">
                <h4 style="margin-bottom: 12px;">Add a Review</h4>
                <form action="{{ route('admin.product-reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="form-row" style="margin-bottom: 12px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <input type="text" name="customer_name" class="form-control" placeholder="Customer Name"
                                required>
                        </div>
                        <div class="form-group" style="margin-bottom: 0; max-width: 120px;">
                            <select name="rating" class="form-control" required>
                                <option value="5">⭐⭐⭐⭐⭐</option>
                                <option value="4">⭐⭐⭐⭐</option>
                                <option value="3">⭐⭐⭐</option>
                                <option value="2">⭐⭐</option>
                                <option value="1">⭐</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 12px;">
                        <textarea name="review_text" class="form-control" rows="2"
                            placeholder="Write the review text here..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-outline btn-sm">Add Review</button>
                </form>
            </div>

            {{-- Existing Reviews List --}}
            @if($product->reviews && $product->reviews->count() > 0)
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border-color); text-align: left;">
                            <th style="padding: 12px 8px;">Customer</th>
                            <th style="padding: 12px 8px;">Rating</th>
                            <th style="padding: 12px 8px;">Review Text</th>
                            <th style="padding: 12px 8px; text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->reviews as $review)
                            <tr style="border-bottom: 1px solid var(--border-color);">
                                <td style="padding: 12px 8px;"><strong>{{ $review->customer_name }}</strong></td>
                                <td style="padding: 12px 8px; color: #f59e0b;">{{ str_repeat('⭐', $review->rating) }}</td>
                                <td style="padding: 12px 8px; color: var(--text-secondary); max-width: 300px;">
                                    {{ $review->review_text }}</td>
                                <td style="padding: 12px 8px; text-align: right;">
                                    <form action="{{ route('admin.product-reviews.destroy', $review) }}" method="POST"
                                        onsubmit="return confirm('Delete this review?');" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline btn-sm"
                                            style="color: var(--danger); border-color: var(--danger);">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="color: var(--text-muted); text-align: center; padding: 20px;">No reviews added yet.</p>
            @endif
        </div>
    </div>
@endsection