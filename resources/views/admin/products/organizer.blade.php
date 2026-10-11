@extends('admin.layouts.app')
@section('title', 'Product Organizer')
@section('page_title', 'Product Organizer & Serial Manager')

@section('header_actions')
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline btn-sm">← Back to Products</a>
    <button type="button" onclick="saveOrder()" id="btnTopSave" class="btn btn-primary btn-sm" style="display: flex; align-items: center; gap: 6px;">
        <span id="saveTopSpinner" style="display: none;">⏳</span>
        <span>💾 Save Order</span>
    </button>
@endsection

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px;">

    {{-- SCOPE SELECTOR TABS --}}
    <div class="admin-card" style="padding: 16px 20px; background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border-color);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
            <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                <span style="font-size: 0.82rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">
                    Organize For:
                </span>

                {{-- Scope 1: Featured --}}
                <a href="{{ route('admin.products.organizer', ['scope' => 'featured']) }}" 
                   style="padding: 8px 18px; border-radius: 8px; font-size: 0.9rem; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: all 0.2s; {{ $scope === 'featured' ? 'background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #000; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);' : 'background: var(--bg-tertiary); color: var(--text-secondary); border: 1px solid var(--border-color);' }}">
                    <span>⭐ Featured Products</span>
                    <span style="font-size: 0.75rem; padding: 2px 8px; border-radius: 12px; font-weight: 700; {{ $scope === 'featured' ? 'background: rgba(0,0,0,0.2); color: #000;' : 'background: rgba(245, 158, 11, 0.15); color: #f59e0b;' }}">
                        {{ $featuredCount }}
                    </span>
                </a>

                {{-- Scope 2: By Category --}}
                <a href="{{ route('admin.products.organizer', ['scope' => 'category', 'category_id' => $selectedCategoryId ?? ($categories->first()->id ?? null)]) }}" 
                   style="padding: 8px 18px; border-radius: 8px; font-size: 0.9rem; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: all 0.2s; {{ $scope === 'category' ? 'background: var(--accent-gradient); color: white; box-shadow: 0 4px 12px rgba(124, 58, 237, 0.25);' : 'background: var(--bg-tertiary); color: var(--text-secondary); border: 1px solid var(--border-color);' }}">
                    <span>📂 Category Wise</span>
                </a>

                {{-- Scope 3: All Products --}}
                <a href="{{ route('admin.products.organizer', ['scope' => 'all']) }}" 
                   style="padding: 8px 18px; border-radius: 8px; font-size: 0.9rem; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: all 0.2s; {{ $scope === 'all' ? 'background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);' : 'background: var(--bg-tertiary); color: var(--text-secondary); border: 1px solid var(--border-color);' }}">
                    <span>🌐 All Products Catalog</span>
                    <span style="font-size: 0.75rem; padding: 2px 8px; border-radius: 12px; font-weight: 700; {{ $scope === 'all' ? 'background: rgba(255,255,255,0.2); color: white;' : 'background: rgba(255,255,255,0.06); color: var(--text-muted);' }}">
                        {{ $totalProducts }}
                    </span>
                </a>
            </div>

            {{-- Category Filter Dropdown (When on category or all mode) --}}
            @if($scope === 'category' || $scope === 'all')
            <div style="display: flex; align-items: center; gap: 8px;">
                <label for="categoryFilter" style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">Category:</label>
                <select id="categoryFilter" class="form-control" onchange="window.location.href='{{ route('admin.products.organizer') }}?scope={{ $scope }}&category_id=' + this.value" style="background: var(--bg-tertiary); color: white; border: 1px solid var(--border-color); border-radius: 8px; padding: 8px 12px; font-size: 0.88rem;">
                    @if($scope === 'all')
                        <option value="">All Categories</option>
                    @endif
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ ($selectedCategoryId == $cat->id) ? 'selected' : '' }}>
                            {{ $cat->name }} ({{ $cat->products_count }})
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
        </div>
    </div>

    {{-- INSTRUCTION BANNER --}}
    <div style="background: rgba(124, 58, 237, 0.08); border: 1px solid rgba(124, 58, 237, 0.25); border-radius: 10px; padding: 14px 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <span style="font-size: 1.5rem;">
                @if($scope === 'featured') ⭐ @elseif($scope === 'category') 📂 @else 🌐 @endif
            </span>
            <div>
                <h4 style="margin: 0; color: #fff; font-size: 0.98rem; font-weight: 700;">
                    @if($scope === 'featured')
                        Organizing Featured Products Serial Order
                    @elseif($scope === 'category')
                        Organizing Category Products Serial ({{ $categories->firstWhere('id', $selectedCategoryId)->name ?? 'Selected Category' }})
                    @else
                        Organizing General Store Products Serial
                    @endif
                </h4>
                <p style="margin: 3px 0 0; color: var(--text-muted); font-size: 0.82rem;">
                    Drag & drop rows using <strong>⠿</strong>, or change the <strong>Serial #</strong> directly. Products at the top appear first on the website!
                </p>
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 12px;">
            <label style="display: flex; align-items: center; gap: 6px; font-size: 0.82rem; color: #cbd5e1; cursor: pointer;">
                <input type="checkbox" id="autoSaveToggle" checked style="accent-color: var(--accent-primary);">
                Auto-save on drag
            </label>
            <button type="button" onclick="autoRenumber()" class="btn btn-sm" style="background: rgba(255,255,255,0.06); color: var(--text-secondary); border: 1px solid var(--border-color); border-radius: 6px; padding: 6px 12px; font-size: 0.8rem; cursor: pointer;">
                🔢 Renumber 1..N
            </button>
            <button type="button" onclick="saveOrder()" id="btnActionSave" class="btn btn-sm btn-primary" style="display: flex; align-items: center; gap: 6px;">
                <span id="saveActionSpinner" style="display: none;">⏳</span>
                <span>💾 Save Order</span>
            </button>
        </div>
    </div>

    {{-- SEARCH & FILTER TOOLBAR --}}
    <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
        <div class="search-bar" style="max-width: 340px; width: 100%;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg>
            <input type="text" id="filterInput" onkeyup="filterItems()" placeholder="Filter by product name in this list...">
        </div>
        <div style="font-size: 0.85rem; color: var(--text-muted);">
            Showing <strong style="color: #fff;" id="itemCountDisplay">{{ $products->count() }}</strong> product(s)
        </div>
    </div>

    {{-- ORGANIZER LIST TABLE / CONTAINER --}}
    <div class="admin-card" style="padding: 0; background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border-color); overflow: hidden;">
        @if($products->count() > 0)
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;" id="organizerTable">
                    <thead>
                        <tr style="background: rgba(255,255,255,0.02); border-bottom: 1px solid var(--border-color); color: var(--text-muted); font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.8px;">
                            <th style="padding: 14px 16px; width: 50px; text-align: center;">Drag</th>
                            <th style="padding: 14px 12px; width: 90px; text-align: center;">Serial #</th>
                            <th style="padding: 14px 16px; width: 70px;">Image</th>
                            <th style="padding: 14px 16px;">Product Title</th>
                            <th style="padding: 14px 16px; width: 140px;">Category</th>
                            <th style="padding: 14px 16px; width: 110px;">Price</th>
                            <th style="padding: 14px 16px; width: 110px; text-align: center;">Featured</th>
                            <th style="padding: 14px 16px; width: 140px; text-align: right;">Move Actions</th>
                        </tr>
                    </thead>
                    <tbody id="sortableList">
                        @foreach($products as $index => $product)
                            @php
                                $currentSerial = ($scope === 'featured') ? ($product->featured_order ?: ($index + 1)) : ($product->sort_order ?: ($index + 1));
                            @endphp
                            <tr class="organizer-row" 
                                draggable="true" 
                                data-id="{{ $product->id }}" 
                                data-name="{{ strtolower($product->name . ' ' . ($product->name_bn ?? '')) }}"
                                style="border-bottom: 1px solid var(--border-color); transition: background 0.15s ease; cursor: grab;">
                                
                                {{-- Drag Handle --}}
                                <td style="padding: 12px 16px; text-align: center; color: var(--text-muted); cursor: grab;">
                                    <span class="drag-handle" style="font-size: 1.3rem; line-height: 1; user-select: none; display: inline-block; padding: 4px;">⠿</span>
                                </td>

                                {{-- Serial Number Input & Badge --}}
                                <td style="padding: 12px 12px; text-align: center;">
                                    <div style="display: flex; align-items: center; justify-content: center; gap: 4px;">
                                        <input type="number" 
                                               min="1" 
                                               class="serial-input form-control" 
                                               value="{{ $currentSerial }}" 
                                               onchange="onSerialInputChange(this)"
                                               style="width: 58px; text-align: center; background: var(--bg-tertiary); color: #fff; border: 1px solid var(--border-color); border-radius: 6px; padding: 4px; font-weight: 700; font-size: 0.9rem;">
                                    </div>
                                </td>

                                {{-- Image --}}
                                <td style="padding: 12px 16px;">
                                    @if($product->image)
                                        <img src="{{ asset('uploads/' . $product->image) }}" alt="{{ $product->name }}" 
                                             style="width: 44px; height: 44px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border-color); display: block;">
                                    @else
                                        <div style="width: 44px; height: 44px; border-radius: 6px; background: var(--bg-tertiary); display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 1.2rem;">
                                            📦
                                        </div>
                                    @endif
                                </td>

                                {{-- Name & Slug --}}
                                <td style="padding: 12px 16px;">
                                    <div style="font-weight: 600; color: #fff; font-size: 0.92rem; margin-bottom: 2px;">
                                        <a href="{{ route('admin.products.edit', $product) }}" target="_blank" style="color: #fff; text-decoration: none;" title="Edit Product">
                                            {{ $product->name }} ↗
                                        </a>
                                    </div>
                                    @if($product->name_bn)
                                        <div style="font-size: 0.8rem; color: var(--text-muted);">
                                            {{ $product->name_bn }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Category --}}
                                <td style="padding: 12px 16px;">
                                    <span style="font-size: 0.8rem; padding: 3px 8px; background: rgba(255,255,255,0.06); border: 1px solid var(--border-color); border-radius: 12px; color: var(--text-secondary); white-space: nowrap;">
                                        {{ $product->category ? $product->category->name : 'Uncategorized' }}
                                    </span>
                                </td>

                                {{-- Price --}}
                                <td style="padding: 12px 16px; font-weight: 600; color: #cbd5e1; white-space: nowrap;">
                                    ৳{{ number_format($product->sale_price ?? $product->price, 2) }}
                                </td>

                                {{-- Featured Toggle --}}
                                <td style="padding: 12px 16px; text-align: center;">
                                    <button type="button" 
                                            onclick="toggleFeaturedAjax({{ $product->id }}, this)" 
                                            title="Toggle Featured"
                                            style="background: none; border: none; font-size: 1.25rem; cursor: pointer; transition: transform 0.15s; padding: 4px;"
                                            onmouseover="this.style.transform='scale(1.2)'"
                                            onmouseout="this.style.transform='scale(1)'">
                                        @if($product->is_featured)
                                            <span style="color: #f59e0b;">⭐</span>
                                        @else
                                            <span style="color: rgba(255,255,255,0.2);">☆</span>
                                        @endif
                                    </button>
                                </td>

                                {{-- Move Action Buttons --}}
                                <td style="padding: 12px 16px; text-align: right; white-space: nowrap;">
                                    <div style="display: inline-flex; gap: 4px;">
                                        <button type="button" onclick="moveRowTop(this)" title="Send to Top" class="btn-order-tool">
                                            🔝
                                        </button>
                                        <button type="button" onclick="moveRowUp(this)" title="Move Up" class="btn-order-tool">
                                            ⬆
                                        </button>
                                        <button type="button" onclick="moveRowDown(this)" title="Move Down" class="btn-order-tool">
                                            ⬇
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px; color: var(--text-muted);">
                <div style="font-size: 3rem; margin-bottom: 12px;">
                    @if($scope === 'featured') ⭐ @else 📦 @endif
                </div>
                <h3 style="color: #fff; margin-bottom: 8px;">No Products Found</h3>
                <p style="margin: 0 0 20px; font-size: 0.9rem;">
                    @if($scope === 'featured')
                        There are currently no products marked as "Featured".<br>
                        Switch to <strong>All Products</strong> or click the ⭐ icon to feature products.
                    @else
                        No products exist in this category.
                    @endif
                </p>
                @if($scope === 'featured')
                    <a href="{{ route('admin.products.organizer', ['scope' => 'all']) }}" class="btn btn-primary btn-sm">
                        View All Products to Select Featured →
                    </a>
                @endif
            </div>
        @endif
    </div>

    {{-- BOTTOM SAVE BAR --}}
    @if($products->count() > 0)
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; padding: 14px 20px; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 10px;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <span id="changeIndicator" style="display: none; font-size: 0.85rem; color: #f59e0b; font-weight: 600;">
                ⚠️ Unsaved order changes
            </span>
            <span id="savedIndicator" style="display: inline; font-size: 0.85rem; color: #10b981; font-weight: 600;">
                ✅ Order saved
            </span>
        </div>
        <div style="display: flex; gap: 10px;">
            <button type="button" onclick="window.location.reload()" class="btn btn-outline btn-sm">
                Cancel / Reset
            </button>
            <button type="submit" onclick="saveOrder()" class="btn btn-primary" style="padding: 8px 24px; font-weight: 700;">
                💾 Save New Order
            </button>
        </div>
    </div>
    @endif
</div>

{{-- TOAST NOTIFICATION --}}
<div id="orderToast" style="display: none; position: fixed; bottom: 24px; right: 24px; background: #10b981; color: white; padding: 12px 20px; border-radius: 8px; font-weight: 600; box-shadow: 0 4px 16px rgba(0,0,0,0.4); z-index: 9999; display: flex; align-items: center; gap: 8px; transform: translateY(100px); transition: transform 0.3s ease, opacity 0.3s ease; opacity: 0;">
    <span id="orderToastIcon">✅</span>
    <span id="orderToastMsg">Product serial order updated!</span>
</div>

{{-- CSS STYLES --}}
<style>
    .organizer-row.dragging {
        opacity: 0.45;
        background: rgba(124, 58, 237, 0.15) !important;
        border: 2px dashed var(--accent-primary) !important;
    }
    .organizer-row.drag-over {
        border-top: 3px solid var(--accent-primary) !important;
        background: rgba(124, 58, 237, 0.08);
    }
    .btn-order-tool {
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.78rem;
        cursor: pointer;
        transition: all 0.15s;
    }
    .btn-order-tool:hover {
        background: rgba(124, 58, 237, 0.2);
        border-color: var(--accent-primary);
        transform: translateY(-1px);
    }
    .serial-input:focus {
        border-color: var(--accent-primary) !important;
        outline: none;
        box-shadow: 0 0 0 2px rgba(124, 58, 237, 0.3);
    }
</style>

{{-- JAVASCRIPT LOGIC --}}
<script>
    const currentScope = '{{ $scope }}';
    let hasUnsavedChanges = false;
    let draggedRow = null;

    // Initialize drag and drop events
    document.addEventListener('DOMContentLoaded', () => {
        setupDragAndDrop();
    });

    function setupDragAndDrop() {
        const rows = document.querySelectorAll('.organizer-row');
        const tbody = document.getElementById('sortableList');

        rows.forEach(row => {
            row.addEventListener('dragstart', (e) => {
                draggedRow = row;
                row.classList.add('dragging');
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', row.dataset.id);
            });

            row.addEventListener('dragend', () => {
                row.classList.remove('dragging');
                document.querySelectorAll('.organizer-row').forEach(r => r.classList.remove('drag-over'));
                draggedRow = null;
            });

            row.addEventListener('dragover', (e) => {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
                if (row !== draggedRow) {
                    row.classList.add('drag-over');
                }
            });

            row.addEventListener('dragleave', () => {
                row.classList.remove('drag-over');
            });

            row.addEventListener('drop', (e) => {
                e.preventDefault();
                row.classList.remove('drag-over');
                if (draggedRow && draggedRow !== row) {
                    const allRows = Array.from(tbody.querySelectorAll('.organizer-row'));
                    const draggedIndex = allRows.indexOf(draggedRow);
                    const targetIndex = allRows.indexOf(row);

                    if (draggedIndex < targetIndex) {
                        tbody.insertBefore(draggedRow, row.nextSibling);
                    } else {
                        tbody.insertBefore(draggedRow, row);
                    }

                    recalculateSerials();
                    markChanged();

                    if (document.getElementById('autoSaveToggle').checked) {
                        saveOrder(true);
                    }
                }
            });
        });
    }

    // Directional button actions
    function moveRowUp(btn) {
        const row = btn.closest('.organizer-row');
        const prev = row.previousElementSibling;
        if (prev && prev.classList.contains('organizer-row')) {
            row.parentNode.insertBefore(row, prev);
            recalculateSerials();
            markChanged();
            if (document.getElementById('autoSaveToggle').checked) saveOrder(true);
        }
    }

    function moveRowDown(btn) {
        const row = btn.closest('.organizer-row');
        const next = row.nextElementSibling;
        if (next && next.classList.contains('organizer-row')) {
            row.parentNode.insertBefore(next, row);
            recalculateSerials();
            markChanged();
            if (document.getElementById('autoSaveToggle').checked) saveOrder(true);
        }
    }

    function moveRowTop(btn) {
        const row = btn.closest('.organizer-row');
        const tbody = document.getElementById('sortableList');
        if (tbody.firstElementChild !== row) {
            tbody.insertBefore(row, tbody.firstElementChild);
            recalculateSerials();
            markChanged();
            if (document.getElementById('autoSaveToggle').checked) saveOrder(true);
        }
    }

    // Direct serial number input changes
    function onSerialInputChange(input) {
        const row = input.closest('.organizer-row');
        const newSerial = parseInt(input.value) || 1;
        const tbody = document.getElementById('sortableList');
        const rows = Array.from(tbody.querySelectorAll('.organizer-row'));

        // Re-sort DOM rows based on current input values
        rows.sort((a, b) => {
            const valA = parseInt(a.querySelector('.serial-input').value) || 999999;
            const valB = parseInt(b.querySelector('.serial-input').value) || 999999;
            return valA - valB;
        });

        rows.forEach(r => tbody.appendChild(r));
        recalculateSerials();
        markChanged();
    }

    // Automatically renumber from 1 to N
    function autoRenumber() {
        const inputs = document.querySelectorAll('.serial-input');
        inputs.forEach((input, idx) => {
            input.value = idx + 1;
        });
        markChanged();
        if (document.getElementById('autoSaveToggle').checked) {
            saveOrder(true);
        }
    }

    // Recalculate serials sequentially from current DOM order
    function recalculateSerials() {
        const rows = document.querySelectorAll('.organizer-row');
        rows.forEach((row, idx) => {
            const input = row.querySelector('.serial-input');
            if (input) {
                input.value = idx + 1;
            }
        });
    }

    function markChanged() {
        hasUnsavedChanges = true;
        document.getElementById('changeIndicator').style.display = 'inline';
        document.getElementById('savedIndicator').style.display = 'none';
    }

    // Save orders to server via AJAX
    function saveOrder(isAuto = false) {
        const rows = document.querySelectorAll('.organizer-row');
        const orderData = [];

        rows.forEach((row, index) => {
            const id = parseInt(row.dataset.id);
            const serialInput = row.querySelector('.serial-input');
            const serial = serialInput ? parseInt(serialInput.value) : (index + 1);

            orderData.push({
                id: id,
                order: serial
            });
        });

        if (orderData.length === 0) return;

        const spinnerTop = document.getElementById('saveTopSpinner');
        const spinnerAction = document.getElementById('saveActionSpinner');
        if (spinnerTop) spinnerTop.style.display = 'inline';
        if (spinnerAction) spinnerAction.style.display = 'inline';

        fetch('{{ route("admin.products.organizer.update-order") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                scope: currentScope,
                orders: orderData
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                hasUnsavedChanges = false;
                document.getElementById('changeIndicator').style.display = 'none';
                document.getElementById('savedIndicator').style.display = 'inline';
                showToast(isAuto ? 'Order updated (Auto-saved)' : '✅ Product order saved successfully!');
            } else {
                alert('Failed to save order: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(err => {
            alert('Error saving order: ' + err.message);
        })
        .finally(() => {
            if (spinnerTop) spinnerTop.style.display = 'none';
            if (spinnerAction) spinnerAction.style.display = 'none';
        });
    }

    // Toggle Featured directly
    function toggleFeaturedAjax(productId, btn) {
        fetch(`/admin/products/${productId}/toggle-featured`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (data.is_featured) {
                    btn.innerHTML = '<span style="color: #f59e0b;">⭐</span>';
                } else {
                    btn.innerHTML = '<span style="color: rgba(255,255,255,0.2);">☆</span>';
                    // If on featured tab, optionally hide or highlight
                    if (currentScope === 'featured') {
                        btn.closest('.organizer-row').style.opacity = '0.4';
                    }
                }
                showToast(data.message);
            }
        })
        .catch(err => alert('Error: ' + err.message));
    }

    // Filter table rows by text
    function filterItems() {
        const query = document.getElementById('filterInput').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.organizer-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const text = row.dataset.name;
            if (text.includes(query)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('itemCountDisplay').textContent = visibleCount;
    }

    // Toast helper
    function showToast(message, isError = false) {
        const toast = document.getElementById('orderToast');
        const toastMsg = document.getElementById('orderToastMsg');
        const toastIcon = document.getElementById('orderToastIcon');

        toastMsg.textContent = message;
        toastIcon.textContent = isError ? '❌' : '✅';
        toast.style.background = isError ? '#ef4444' : '#10b981';

        toast.style.display = 'flex';
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(100px)';
            setTimeout(() => { toast.style.display = 'none'; }, 300);
        }, 2200);
    }
</script>
@endsection
