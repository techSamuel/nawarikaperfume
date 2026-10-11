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
    @php
        $geminiKey = \App\Models\Setting::get('gemini_api_key') ?: config('services.gemini.api_key');
        $geminiModel = \App\Models\Setting::get('gemini_model') ?: config('services.gemini.model', 'gemini-2.5-flash');
        $defaultLang = \App\Models\Setting::get('gemini_default_review_lang', 'mixed');
        $defaultRatingStyle = \App\Models\Setting::get('gemini_default_rating_style', 'mostly_5');
    @endphp

    <div class="admin-card" style="max-width:800px; margin-top: 32px;">
        <div class="admin-card-header" style="padding: 16px 24px; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
            <div>
                <h3 style="margin: 0; display: flex; align-items: center; gap: 8px;">
                    <span>Customer Reviews</span>
                    <span style="font-size: 0.8rem; padding: 2px 8px; background: rgba(124,58,237,0.2); color: #c4b5fd; border-radius: 12px; font-weight: 500;" id="reviewsCountBadge">
                        {{ $product->reviews ? $product->reviews->count() : 0 }} Reviews
                    </span>
                </h3>
            </div>
            <button type="button" onclick="toggleAiReviewPanel()" class="btn btn-sm" style="background: linear-gradient(135deg, #7c3aed 0%, #3b82f6 100%); color: white; border: none; padding: 8px 16px; border-radius: 6px; font-weight: 600; display: flex; align-items: center; gap: 6px; cursor: pointer; box-shadow: 0 2px 8px rgba(124,58,237,0.25);">
                <span>✨ Generate Reviews by AI</span>
            </button>
        </div>
        <div class="admin-card-body" style="padding: 24px;">

            {{-- AI REVIEWS GENERATOR CARD --}}
            <div id="aiReviewPanel" style="background: linear-gradient(135deg, rgba(124, 58, 237, 0.08) 0%, rgba(59, 130, 246, 0.04) 100%); border: 1px solid rgba(124, 58, 237, 0.3); border-radius: 12px; padding: 20px; margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 8px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-size: 1.4rem;">🤖</span>
                        <div>
                            <h4 style="margin: 0; color: #fff; font-size: 1.05rem;">Google Gemini AI Review Generator</h4>
                            <p style="margin: 2px 0 0; color: var(--text-muted); font-size: 0.8rem;">
                                Generating authentic reviews for: <strong>{{ Str::limit($product->name, 45) }}</strong>
                            </p>
                        </div>
                    </div>
                    <span style="font-size: 0.72rem; padding: 3px 8px; background: rgba(59, 130, 246, 0.15); color: #93c5fd; border-radius: 12px; font-family: monospace;">
                        Model: {{ $geminiModel }}
                    </span>
                </div>

                @if(empty($geminiKey))
                    <div style="background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.3); color: #fde68a; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 0.85rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                        <span>⚠️ Gemini API Key is not configured yet. Add your key to start generating reviews.</span>
                        <a href="{{ route('admin.settings.index', ['tab' => 'ai']) }}" class="btn btn-sm" style="background: #f59e0b; color: #000; text-decoration: none; padding: 4px 12px; border-radius: 6px; font-weight: 600;">
                            Configure API Key →
                        </a>
                    </div>
                @endif

                {{-- Generation Controls --}}
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin-bottom: 16px;">
                    {{-- Review Count --}}
                    <div>
                        <label style="display:block; margin-bottom: 6px; font-size: 0.85rem; font-weight: 600; color: #cbd5e1;">Review Count</label>
                        <div style="display: flex; gap: 6px;">
                            <select id="aiReviewCount" class="form-control" style="background: var(--bg-tertiary); color: white; border: 1px solid var(--border-color); border-radius: 6px; padding: 8px 10px; font-size: 0.9rem; width: 100%;">
                                <option value="1">1 (Single Review)</option>
                                <option value="2">2 Reviews</option>
                                <option value="3" selected>3 Reviews</option>
                                <option value="5">5 Reviews</option>
                                <option value="10">10 Reviews</option>
                            </select>
                        </div>
                    </div>

                    {{-- Rating Style --}}
                    <div>
                        <label style="display:block; margin-bottom: 6px; font-size: 0.85rem; font-weight: 600; color: #cbd5e1;">Rating Style</label>
                        <select id="aiRatingStyle" class="form-control" style="background: var(--bg-tertiary); color: white; border: 1px solid var(--border-color); border-radius: 6px; padding: 8px 10px; font-size: 0.9rem; width: 100%;">
                            <option value="mostly_5" {{ $defaultRatingStyle == 'mostly_5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ Mostly 5★ (Realistic)</option>
                            <option value="all_5" {{ $defaultRatingStyle == 'all_5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ 5 Stars Only</option>
                            <option value="mixed" {{ $defaultRatingStyle == 'mixed' ? 'selected' : '' }}>⭐⭐⭐ to ⭐⭐⭐⭐⭐ 3-5★ Mix</option>
                        </select>
                    </div>

                    {{-- Language --}}
                    <div>
                        <label style="display:block; margin-bottom: 6px; font-size: 0.85rem; font-weight: 600; color: #cbd5e1;">Language</label>
                        <select id="aiLanguage" class="form-control" style="background: var(--bg-tertiary); color: white; border: 1px solid var(--border-color); border-radius: 6px; padding: 8px 10px; font-size: 0.9rem; width: 100%;">
                            <option value="mixed" {{ $defaultLang == 'mixed' ? 'selected' : '' }}>Natural Blend (BD Shoppers)</option>
                            <option value="bangla" {{ $defaultLang == 'bangla' ? 'selected' : '' }}>Bengali only (বাংলা)</option>
                            <option value="banglish" {{ $defaultLang == 'banglish' ? 'selected' : '' }}>Banglish (Romanized)</option>
                            <option value="english" {{ $defaultLang == 'english' ? 'selected' : '' }}>English</option>
                        </select>
                    </div>
                </div>

                {{-- Custom instructions (Optional) --}}
                <div style="margin-bottom: 16px;">
                    <label style="display:block; margin-bottom: 6px; font-size: 0.82rem; color: var(--text-muted);">
                        Special Focus / Instructions (Optional):
                    </label>
                    <input type="text" id="aiCustomNotes" class="form-control" placeholder="e.g. Highlight long lasting fragrance, nice packaging, compliments from colleagues..." style="background: var(--bg-tertiary); color: white; border: 1px solid var(--border-color); border-radius: 6px; padding: 8px 12px; font-size: 0.85rem; width: 100%;">
                </div>

                {{-- Action Buttons --}}
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <button type="button" id="btnAiPreview" onclick="generateAiReviews(false)" class="btn" style="background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%); color: white; border: none; border-radius: 6px; padding: 10px 18px; font-size: 0.88rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                        <span id="aiPreviewSpinner" style="display: none;">⏳</span>
                        <span>✨ Generate & Preview</span>
                    </button>

                    <button type="button" id="btnAiQuickSave" onclick="generateAiReviews(true)" class="btn" style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.4); color: #34d399; border-radius: 6px; padding: 10px 18px; font-size: 0.88rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                        <span id="aiQuickSpinner" style="display: none;">⏳</span>
                        <span>⚡ 1-Click Auto Generate & Save</span>
                    </button>
                </div>

                {{-- Live Error Box --}}
                <div id="aiErrorAlert" style="display: none; margin-top: 14px; padding: 12px 16px; background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5; border-radius: 8px; font-size: 0.85rem;"></div>

                {{-- PREVIEW CONTAINER --}}
                <div id="aiPreviewContainer" style="display: none; margin-top: 20px; border-top: 1px solid rgba(124, 58, 237, 0.2); padding-top: 18px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 8px;">
                        <h4 style="margin: 0; color: #fff; font-size: 0.95rem; display: flex; align-items: center; gap: 6px;">
                            <span>📋 Generated Reviews Preview</span>
                            <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">(You can edit any review before saving)</span>
                        </h4>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <button type="button" onclick="toggleSelectAllAiReviews(true)" style="background: none; border: none; color: #a78bfa; font-size: 0.78rem; cursor: pointer; text-decoration: underline;">Select All</button>
                            <span style="color: var(--text-muted);">|</span>
                            <button type="button" onclick="toggleSelectAllAiReviews(false)" style="background: none; border: none; color: var(--text-muted); font-size: 0.78rem; cursor: pointer; text-decoration: underline;">Deselect All</button>
                        </div>
                    </div>

                    <div id="aiReviewsList" style="display: flex; flex-direction: column; gap: 12px; max-height: 480px; overflow-y: auto; padding-right: 4px;">
                        {{-- Populated dynamically --}}
                    </div>

                    <div style="margin-top: 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                        <button type="button" onclick="saveSelectedAiReviews()" id="btnSaveSelectedAi" class="btn" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                            <span id="saveSelectedSpinner" style="display: none;">⏳</span>
                            <span>💾 Save Selected Reviews to Product</span>
                        </button>
                        <button type="button" onclick="document.getElementById('aiPreviewContainer').style.display = 'none'" class="btn btn-sm" style="background: rgba(255,255,255,0.06); color: var(--text-secondary); border: 1px solid var(--border-color); border-radius: 6px; padding: 8px 14px; cursor: pointer;">
                            Dismiss Preview
                        </button>
                    </div>
                </div>
            </div>

            {{-- Add New Review Form (Manual) --}}
            <div style="background: var(--bg-tertiary); padding: 18px; border-radius: 8px; margin-bottom: 24px; border: 1px solid var(--border-color);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 8px;">
                    <h4 style="margin: 0; font-size: 1rem;">Add a Review Manually</h4>
                    <button type="button" onclick="autoFillSingleReviewWithAi()" id="btnAutoFillForm" class="btn btn-sm" style="background: rgba(124,58,237,0.15); border: 1px solid rgba(124,58,237,0.4); color: #c4b5fd; border-radius: 6px; padding: 5px 12px; font-size: 0.78rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                        <span id="autoFillSpinner" style="display: none;">⏳</span>
                        <span>🪄 AI Auto-Fill Form</span>
                    </button>
                </div>
                <form action="{{ route('admin.product-reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="form-row" style="margin-bottom: 12px; display: flex; gap: 12px;">
                        <div class="form-group" style="margin-bottom: 0; flex: 1;">
                            <input type="text" name="customer_name" id="manualCustomerName" class="form-control" placeholder="Customer Name (e.g. Tanvir Ahmed)" required style="width: 100%; padding: 10px; background: var(--bg-secondary); border: 1px solid var(--border-color); color: white; border-radius: 6px;">
                        </div>
                        <div class="form-group" style="margin-bottom: 0; width: 140px;">
                            <select name="rating" id="manualRating" class="form-control" required style="width: 100%; padding: 10px; background: var(--bg-secondary); border: 1px solid var(--border-color); color: white; border-radius: 6px;">
                                <option value="5">⭐⭐⭐⭐⭐</option>
                                <option value="4">⭐⭐⭐⭐</option>
                                <option value="3">⭐⭐⭐</option>
                                <option value="2">⭐⭐</option>
                                <option value="1">⭐</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 12px;">
                        <textarea name="review_text" id="manualReviewText" class="form-control" rows="2" placeholder="Write the review text here..." required style="width: 100%; padding: 10px; background: var(--bg-secondary); border: 1px solid var(--border-color); color: white; border-radius: 6px;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-outline btn-sm">Add Review</button>
                </form>
            </div>

            {{-- Existing Reviews List --}}
            <div id="existingReviewsSection">
                @if($product->reviews && $product->reviews->count() > 0)
                    <table style="width: 100%; border-collapse: collapse;" id="reviewsTable">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--border-color); text-align: left;">
                                <th style="padding: 12px 8px; color: var(--text-muted); font-size: 0.85rem;">Customer</th>
                                <th style="padding: 12px 8px; color: var(--text-muted); font-size: 0.85rem;">Rating</th>
                                <th style="padding: 12px 8px; color: var(--text-muted); font-size: 0.85rem;">Review Text</th>
                                <th style="padding: 12px 8px; text-align: right; color: var(--text-muted); font-size: 0.85rem;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="reviewsTableBody">
                            @foreach($product->reviews as $review)
                                <tr style="border-bottom: 1px solid var(--border-color);" id="review-row-{{ $review->id }}">
                                    <td style="padding: 12px 8px;"><strong>{{ $review->customer_name }}</strong></td>
                                    <td style="padding: 12px 8px; color: #f59e0b; white-space: nowrap;">{{ str_repeat('⭐', $review->rating) }}</td>
                                    <td style="padding: 12px 8px; color: var(--text-secondary); max-width: 340px; font-size: 0.9rem; line-height: 1.5;">
                                        {{ $review->review_text }}
                                    </td>
                                    <td style="padding: 12px 8px; text-align: right;">
                                        <form action="{{ route('admin.product-reviews.destroy', $review) }}" method="POST"
                                            onsubmit="return confirm('Delete this review?');" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline btn-sm"
                                                style="color: var(--danger); border-color: var(--danger); padding: 4px 8px; font-size: 0.75rem;">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div id="noReviewsPlaceholder" style="color: var(--text-muted); text-align: center; padding: 32px; background: rgba(255,255,255,0.01); border-radius: 8px; border: 1px dashed var(--border-color);">
                        <span style="font-size: 2rem; display: block; margin-bottom: 6px;">💬</span>
                        No reviews added yet. Click <strong>Generate Reviews by AI</strong> above to create reviews instantly!
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT FOR AI REVIEWS GENERATOR --}}
    <script>
        function toggleAiReviewPanel() {
            const panel = document.getElementById('aiReviewPanel');
            if (panel.style.display === 'none') {
                panel.style.display = 'block';
                panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } else {
                panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }

        // Generate AI reviews (preview or auto-save)
        function generateAiReviews(autoSave = false) {
            const count = document.getElementById('aiReviewCount').value;
            const ratingStyle = document.getElementById('aiRatingStyle').value;
            const language = document.getElementById('aiLanguage').value;
            const customNotes = document.getElementById('aiCustomNotes').value.trim();

            const btnPreview = document.getElementById('btnAiPreview');
            const btnQuick = document.getElementById('btnAiQuickSave');
            const errAlert = document.getElementById('aiErrorAlert');
            const previewContainer = document.getElementById('aiPreviewContainer');

            errAlert.style.display = 'none';

            if (autoSave) {
                btnQuick.disabled = true;
                btnQuick.style.opacity = '0.7';
                document.getElementById('aiQuickSpinner').style.display = 'inline';
            } else {
                btnPreview.disabled = true;
                btnPreview.style.opacity = '0.7';
                document.getElementById('aiPreviewSpinner').style.display = 'inline';
            }

            fetch('{{ route("admin.products.generate-ai-reviews", $product) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    count: parseInt(count),
                    rating_style: ratingStyle,
                    language: language,
                    custom_notes: customNotes,
                    auto_save: autoSave
                })
            })
            .then(res => res.json().then(data => ({ status: res.status, ok: res.ok, data })))
            .then(({ ok, data }) => {
                if (!ok || !data.success) {
                    throw new Error(data.message || 'Error occurred while generating reviews.');
                }

                if (autoSave) {
                    // Instantly saved! Reload to show updated table & notification
                    window.location.reload();
                } else {
                    // Render preview list
                    renderAiReviewsPreview(data.reviews);
                    previewContainer.style.display = 'block';
                    previewContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            })
            .catch(err => {
                errAlert.style.display = 'block';
                errAlert.innerHTML = '<strong>Generation Failed:</strong> ' + err.message + '<br><small style="color:var(--text-muted);">Please verify your Gemini API key and model in Settings > AI Settings.</small>';
            })
            .finally(() => {
                btnPreview.disabled = false;
                btnPreview.style.opacity = '1';
                document.getElementById('aiPreviewSpinner').style.display = 'none';

                btnQuick.disabled = false;
                btnQuick.style.opacity = '1';
                document.getElementById('aiQuickSpinner').style.display = 'none';
            });
        }

        // Render reviews into the preview cards
        function renderAiReviewsPreview(reviews) {
            const list = document.getElementById('aiReviewsList');
            list.innerHTML = '';

            reviews.forEach((rev, index) => {
                const item = document.createElement('div');
                item.className = 'ai-preview-item';
                item.style.background = 'rgba(0, 0, 0, 0.3)';
                item.style.border = '1px solid var(--border-color)';
                item.style.borderRadius = '8px';
                item.style.padding = '14px';

                item.innerHTML = `
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; gap: 10px;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 600; color: #fff;">
                            <input type="checkbox" class="ai-rev-check" checked style="accent-color: var(--accent-primary); width: 16px; height: 16px;">
                            <span>Review #${index + 1}</span>
                        </label>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input type="text" class="ai-rev-name form-control" value="${escapeHtml(rev.customer_name)}" style="background: var(--bg-tertiary); color: white; border: 1px solid var(--border-color); border-radius: 4px; padding: 4px 8px; font-size: 0.85rem; width: 170px;" placeholder="Customer Name">
                            <select class="ai-rev-rating form-control" style="background: var(--bg-tertiary); color: white; border: 1px solid var(--border-color); border-radius: 4px; padding: 4px 6px; font-size: 0.85rem; width: 110px;">
                                <option value="5" ${rev.rating == 5 ? 'selected' : ''}>⭐⭐⭐⭐⭐</option>
                                <option value="4" ${rev.rating == 4 ? 'selected' : ''}>⭐⭐⭐⭐</option>
                                <option value="3" ${rev.rating == 3 ? 'selected' : ''}>⭐⭐⭐</option>
                                <option value="2" ${rev.rating == 2 ? 'selected' : ''}>⭐⭐</option>
                                <option value="1" ${rev.rating == 1 ? 'selected' : ''}>⭐</option>
                            </select>
                        </div>
                    </div>
                    <textarea class="ai-rev-text form-control" rows="2" style="width: 100%; background: var(--bg-secondary); color: #e2e8f0; border: 1px solid var(--border-color); border-radius: 6px; padding: 8px 10px; font-size: 0.88rem; line-height: 1.5; resize: vertical;">${escapeHtml(rev.review_text)}</textarea>
                `;
                list.appendChild(item);
            });
        }

        function toggleSelectAllAiReviews(selected) {
            document.querySelectorAll('.ai-rev-check').forEach(chk => {
                chk.checked = selected;
            });
        }

        // Save selected reviews to database
        function saveSelectedAiReviews() {
            const items = document.querySelectorAll('.ai-preview-item');
            const selectedReviews = [];

            items.forEach(el => {
                const check = el.querySelector('.ai-rev-check');
                if (check && check.checked) {
                    const name = el.querySelector('.ai-rev-name').value.trim();
                    const rating = parseInt(el.querySelector('.ai-rev-rating').value);
                    const text = el.querySelector('.ai-rev-text').value.trim();

                    if (name && text) {
                        selectedReviews.push({
                            customer_name: name,
                            rating: rating,
                            review_text: text
                        });
                    }
                }
            });

            if (selectedReviews.length === 0) {
                alert('Please select at least one review to save.');
                return;
            }

            const btnSave = document.getElementById('btnSaveSelectedAi');
            const spinner = document.getElementById('saveSelectedSpinner');
            btnSave.disabled = true;
            btnSave.style.opacity = '0.7';
            spinner.style.display = 'inline';

            fetch('{{ route("admin.products.save-ai-reviews", $product) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    reviews: selectedReviews
                })
            })
            .then(res => res.json().then(data => ({ ok: res.ok, data })))
            .then(({ ok, data }) => {
                if (!ok || !data.success) {
                    throw new Error(data.message || 'Failed to save reviews.');
                }
                // Reload page to reflect newly saved reviews in the table
                window.location.reload();
            })
            .catch(err => {
                alert('Error saving reviews: ' + err.message);
                btnSave.disabled = false;
                btnSave.style.opacity = '1';
                spinner.style.display = 'none';
            });
        }

        // Auto-fill a single AI review into the manual "Add a Review" form
        function autoFillSingleReviewWithAi() {
            const btn = document.getElementById('btnAutoFillForm');
            const spinner = document.getElementById('autoFillSpinner');
            const language = document.getElementById('aiLanguage').value;
            const ratingStyle = document.getElementById('aiRatingStyle').value;

            btn.disabled = true;
            btn.style.opacity = '0.7';
            spinner.style.display = 'inline';

            fetch('{{ route("admin.products.generate-ai-reviews", $product) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    count: 1,
                    rating_style: ratingStyle,
                    language: language,
                    auto_save: false
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.reviews && data.reviews.length > 0) {
                    const rev = data.reviews[0];
                    document.getElementById('manualCustomerName').value = rev.customer_name;
                    document.getElementById('manualRating').value = rev.rating;
                    document.getElementById('manualReviewText').value = rev.review_text;
                    document.getElementById('manualReviewText').focus();
                } else {
                    alert('Could not auto-fill: ' + (data.message || 'No review returned'));
                }
            })
            .catch(err => {
                alert('Auto-fill failed: ' + err.message);
            })
            .finally(() => {
                btn.disabled = false;
                btn.style.opacity = '1';
                spinner.style.display = 'none';
            });
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
@endsection