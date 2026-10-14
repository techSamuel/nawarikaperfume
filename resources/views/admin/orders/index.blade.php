@extends('admin.layouts.app')
@section('title', 'Orders')
@section('page_title', 'Orders')

@section('content')
<div class="admin-toolbar">
    <div class="admin-toolbar-left">
        <div class="admin-filters">
            <a href="{{ route('admin.orders.index') }}" class="filter-btn {{ !request('status') ? 'active' : '' }}">All <span class="count">({{ $statusCounts['all'] }})</span></a>
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="filter-btn {{ request('status') === 'pending' ? 'active' : '' }}">Pending <span class="count">({{ $statusCounts['pending'] }})</span></a>
            <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="filter-btn {{ request('status') === 'processing' ? 'active' : '' }}">Processing <span class="count">({{ $statusCounts['processing'] }})</span></a>
            <a href="{{ route('admin.orders.index', ['status' => 'confirm']) }}" class="filter-btn {{ request('status') === 'confirm' ? 'active' : '' }}">Confirm <span class="count">({{ $statusCounts['confirm'] ?? 0 }})</span></a>
            <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="filter-btn {{ request('status') === 'shipped' ? 'active' : '' }}">Shipped <span class="count">({{ $statusCounts['shipped'] }})</span></a>
            <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="filter-btn {{ request('status') === 'delivered' ? 'active' : '' }}">Delivered <span class="count">({{ $statusCounts['delivered'] }})</span></a>
            <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="filter-btn {{ request('status') === 'cancelled' ? 'active' : '' }}">Cancelled <span class="count">({{ $statusCounts['cancelled'] }})</span></a>
        </div>
    </div>
    <form action="{{ route('admin.orders.index') }}" method="GET">
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <div class="search-bar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search orders...">
        </div>
    </form>
</div>

@if($orders->count() > 0)
    {{-- Bulk Action Bar --}}
    <div id="bulkActionBar" class="bulk-action-bar">
        <div class="bulk-action-left">
            <span class="bulk-selected-badge" id="bulkBadge">
                <span id="selectedCount">0</span> selected
            </span>
            <button type="button" class="btn btn-sm btn-outline" id="selectAllPageBtn" style="padding: 4px 10px; font-size: 0.78rem;">
                Select All
            </button>
            <button type="button" class="btn btn-sm btn-outline" id="clearSelectionBtn" style="padding: 4px 10px; font-size: 0.78rem; display: none;">
                Deselect
            </button>
        </div>
        <div class="bulk-action-right">
            {{-- Bulk Copy buttons --}}
            <div style="display: flex; align-items: center; gap: 6px;">
                <button type="button" class="btn btn-sm btn-outline" id="bulkCopyBtn" title="Copy: OrderId, Product Name, Product Price, Customer Name, Address, Phone number">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                    <span id="bulkCopyText">Bulk Copy</span>
                </button>
                <button type="button" class="btn btn-sm btn-outline" id="bulkCopyTabBtn" title="Copy in Excel/Sheets Tab-delimited format" style="padding: 6px 10px; font-size: 0.75rem; color: var(--text-muted);">
                    Tab / Excel
                </button>
            </div>

            <div class="bulk-divider"></div>

            {{-- Bulk Status Update Form --}}
            <form id="bulkStatusForm" action="{{ route('admin.orders.bulk-status') }}" method="POST" style="display:flex; align-items:center; gap:8px; margin:0;">
                @csrf
                <div id="bulkStatusHiddenInputs"></div>
                <select name="status" id="bulkStatusSelect" class="bulk-select-sm" required>
                    <option value="">-- Change Status --</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="confirm">Confirm</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <button type="submit" class="btn btn-sm btn-primary" id="bulkStatusSubmitBtn">
                    Update Status
                </button>
            </form>

            <div class="bulk-divider"></div>

            {{-- Bulk Delete Form --}}
            <form id="bulkDeleteForm" action="{{ route('admin.orders.bulk-delete') }}" method="POST" style="display:inline; margin:0;">
                @csrf
                <div id="bulkDeleteHiddenInputs"></div>
                <button type="submit" class="btn btn-sm btn-danger" id="bulkDeleteSubmitBtn">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    Bulk Delete
                </button>
            </form>
        </div>
    </div>
@endif

<div class="admin-card">
    <div class="admin-card-body no-padding">
        @if($orders->count() > 0)
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 44px; text-align: center;">
                            <input type="checkbox" id="selectAllCheckbox" class="order-select-checkbox" title="Select all on this page">
                        </th>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        @php
                            $productNames = $order->items->map(function($item) {
                                return $item->quantity > 1 ? ($item->product_name . ' (x' . $item->quantity . ')') : $item->product_name;
                            })->implode(' + ') ?: 'No item';

                            $productPrice = $order->items->sum(function($item) {
                                return (float)$item->price * (int)$item->quantity;
                            });
                            if ($productPrice <= 0 && (float)$order->total > 0) {
                                $productPrice = (float)$order->total;
                            }
                            $formattedPrice = ((float)$productPrice == (int)$productPrice) ? (int)$productPrice : number_format($productPrice, 2, '.', '');

                            $cleanAddress = trim(preg_replace('/\s+/', ' ', str_replace(["\r\n", "\r", "\n"], ' ', $order->address ?? '')));
                            if (!empty($order->city) && !str_contains(strtolower($cleanAddress), strtolower($order->city))) {
                                $cleanAddress .= ($cleanAddress ? ', ' : '') . trim($order->city);
                            }
                            $cleanName = trim($order->name ?? '');
                            $cleanPhone = trim($order->phone ?? '');
                            $orderNumber = $order->order_number;

                            $orderCopyComma = "{$orderNumber}, {$productNames}, {$formattedPrice}, {$cleanName}, {$cleanAddress}, {$cleanPhone}";
                            $orderCopyTab = "{$orderNumber}\t{$productNames}\t{$formattedPrice}\t{$cleanName}\t{$cleanAddress}\t{$cleanPhone}";
                        @endphp
                        <tr id="order-row-{{ $order->id }}">
                            <td style="text-align: center;">
                                <input type="checkbox" 
                                       class="order-checkbox order-select-checkbox" 
                                       value="{{ $order->id }}" 
                                       data-order-number="{{ $order->order_number }}"
                                       data-copy-comma="{{ $orderCopyComma }}"
                                       data-copy-tab="{{ $orderCopyTab }}">
                            </td>
                            <td><a href="{{ route('admin.orders.show', $order) }}" style="font-weight:600;">{{ $order->order_number }}</a></td>
                            <td>
                                <div>
                                    <div style="font-weight:600;">{{ $order->name }}</div>
                                    <div style="font-size:0.75rem; color:var(--text-muted);">{{ $order->email }}</div>
                                </div>
                            </td>
                            <td style="font-size:0.85rem;">{{ $order->phone }}</td>
                            <td>
                                <span title="{{ $productNames }}">{{ $order->items->count() }} item(s)</span>
                            </td>
                            <td style="font-weight:700;">৳{{ number_format($order->total, 2) }}</td>
                            <td><span class="badge badge-info">COD</span></td>
                            <td><span class="badge {{ $order->status_badge }}">{{ ucfirst($order->status) }}</span></td>
                            <td style="color:var(--text-muted); font-size:0.85rem;">{{ $order->created_at->format('M d, Y') }}</td>
                            <td>
                                <div style="display:flex; gap:6px; align-items:center;">
                                    <button type="button" 
                                            class="btn-row-copy single-copy-btn" 
                                            title="Copy: OrderId, Product Name, Product Price, Customer Name, Address, Phone number"
                                            data-copy="{{ $orderCopyComma }}"
                                            data-order-number="{{ $order->order_number }}">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                                        <span>Copy</span>
                                    </button>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline btn-sm">View</a>
                                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete Order #{{ $order->order_number }}? This action cannot be undone.');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); padding: 4px 10px;" title="Delete Order">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state"><div class="empty-icon">🛒</div><h3>No orders found</h3><p>Orders will appear here when customers place them.</p></div>
        @endif
    </div>
</div>
<div class="pagination-wrapper">{{ $orders->links() }}</div>

{{-- Toast Notification --}}
<div id="adminToast" class="admin-toast">
    <span id="toastIcon">✓</span>
    <span id="toastMsg">Notification</span>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    const selectedCountSpan = document.getElementById('selectedCount');
    const clearSelectionBtn = document.getElementById('clearSelectionBtn');
    const selectAllPageBtn = document.getElementById('selectAllPageBtn');
    const bulkCopyBtn = document.getElementById('bulkCopyBtn');
    const bulkCopyText = document.getElementById('bulkCopyText');
    const bulkCopyTabBtn = document.getElementById('bulkCopyTabBtn');
    const bulkStatusForm = document.getElementById('bulkStatusForm');
    const bulkStatusSelect = document.getElementById('bulkStatusSelect');
    const bulkStatusHiddenInputs = document.getElementById('bulkStatusHiddenInputs');
    const bulkDeleteForm = document.getElementById('bulkDeleteForm');
    const bulkDeleteHiddenInputs = document.getElementById('bulkDeleteHiddenInputs');
    const adminToast = document.getElementById('adminToast');
    const toastMsg = document.getElementById('toastMsg');
    const toastIcon = document.getElementById('toastIcon');

    let toastTimer = null;
    function showToast(message, isError = false) {
        if (!adminToast) return;
        toastMsg.textContent = message;
        toastIcon.textContent = isError ? '✕' : '✓';
        adminToast.style.borderColor = isError ? '#ef4444' : 'var(--accent-primary)';
        adminToast.classList.add('show');
        if (toastTimer) clearTimeout(toastTimer);
        toastTimer = setTimeout(() => {
            adminToast.classList.remove('show');
        }, 3000);
    }

    function copyToClipboard(text, onSuccess, onError) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(onSuccess).catch(() => {
                fallbackCopy(text, onSuccess, onError);
            });
        } else {
            fallbackCopy(text, onSuccess, onError);
        }
    }

    function fallbackCopy(text, onSuccess, onError) {
        try {
            const temp = document.createElement('textarea');
            temp.value = text;
            temp.style.position = 'fixed';
            temp.style.left = '-9999px';
            temp.style.top = '-9999px';
            document.body.appendChild(temp);
            temp.focus();
            temp.select();
            const success = document.execCommand('copy');
            document.body.removeChild(temp);
            if (success && onSuccess) onSuccess();
            else if (!success && onError) onError();
        } catch (e) {
            if (onError) onError(e);
        }
    }

    function updateSelectionState() {
        const checked = document.querySelectorAll('.order-checkbox:checked');
        const count = checked.length;
        const total = orderCheckboxes.length;

        if (selectedCountSpan) selectedCountSpan.textContent = count;

        if (clearSelectionBtn) {
            clearSelectionBtn.style.display = count > 0 ? 'inline-block' : 'none';
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.checked = count > 0 && count === total;
            selectAllCheckbox.indeterminate = count > 0 && count < total;
        }

        orderCheckboxes.forEach(cb => {
            const row = document.getElementById('order-row-' + cb.value);
            if (row) {
                if (cb.checked) {
                    row.classList.add('row-selected');
                } else {
                    row.classList.remove('row-selected');
                }
            }
        });
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            orderCheckboxes.forEach(cb => {
                cb.checked = isChecked;
            });
            updateSelectionState();
        });
    }

    if (selectAllPageBtn) {
        selectAllPageBtn.addEventListener('click', function() {
            orderCheckboxes.forEach(cb => {
                cb.checked = true;
            });
            updateSelectionState();
            showToast('Selected all ' + orderCheckboxes.length + ' orders on this page.');
        });
    }

    if (clearSelectionBtn) {
        clearSelectionBtn.addEventListener('click', function() {
            orderCheckboxes.forEach(cb => {
                cb.checked = false;
            });
            updateSelectionState();
            showToast('Deselected all orders.');
        });
    }

    orderCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateSelectionState);
    });

    // Bulk Copy (Comma Separated)
    if (bulkCopyBtn) {
        bulkCopyBtn.addEventListener('click', function() {
            const checked = document.querySelectorAll('.order-checkbox:checked');
            if (checked.length === 0) {
                showToast('Please select at least one order to copy.', true);
                return;
            }

            const lines = [];
            checked.forEach(cb => {
                const data = cb.getAttribute('data-copy-comma');
                if (data) lines.push(data);
            });

            const fullText = lines.join('\n');
            copyToClipboard(fullText, () => {
                showToast('Copied ' + checked.length + ' order(s) details to clipboard!');
                const originalText = bulkCopyText.textContent;
                bulkCopyText.textContent = '✓ Copied!';
                setTimeout(() => {
                    bulkCopyText.textContent = originalText;
                }, 2000);
            }, () => {
                showToast('Could not access clipboard.', true);
            });
        });
    }

    // Bulk Copy (Tab / Excel Separated)
    if (bulkCopyTabBtn) {
        bulkCopyTabBtn.addEventListener('click', function() {
            const checked = document.querySelectorAll('.order-checkbox:checked');
            if (checked.length === 0) {
                showToast('Please select at least one order to copy.', true);
                return;
            }

            const lines = [];
            checked.forEach(cb => {
                const data = cb.getAttribute('data-copy-tab');
                if (data) lines.push(data);
            });

            const fullText = lines.join('\n');
            copyToClipboard(fullText, () => {
                showToast('Copied ' + checked.length + ' order(s) in Excel Tab format!');
            }, () => {
                showToast('Could not access clipboard.', true);
            });
        });
    }

    // Single Row Copy Button
    document.querySelectorAll('.single-copy-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const text = this.getAttribute('data-copy');
            const orderNum = this.getAttribute('data-order-number') || '';
            const span = this.querySelector('span');

            copyToClipboard(text, () => {
                showToast('Copied Order #' + orderNum + ' details!');
                if (span) {
                    const orig = span.textContent;
                    span.textContent = 'Copied!';
                    setTimeout(() => { span.textContent = orig; }, 1500);
                }
            }, () => {
                showToast('Failed to copy.', true);
            });
        });
    });

    // Bulk Status Form Submit
    if (bulkStatusForm) {
        bulkStatusForm.addEventListener('submit', function(e) {
            const checked = document.querySelectorAll('.order-checkbox:checked');
            if (checked.length === 0) {
                e.preventDefault();
                showToast('Please select at least one order to update status.', true);
                return;
            }

            const selectedStatus = bulkStatusSelect ? bulkStatusSelect.value : '';
            if (!selectedStatus) {
                e.preventDefault();
                showToast('Please select a target status from the dropdown.', true);
                return;
            }

            const confirmMsg = 'Are you sure you want to change the status of ' + checked.length + ' selected order(s) to "' + selectedStatus.toUpperCase() + '"?';
            if (!confirm(confirmMsg)) {
                e.preventDefault();
                return;
            }

            if (bulkStatusHiddenInputs) {
                bulkStatusHiddenInputs.innerHTML = '';
                checked.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'order_ids[]';
                    input.value = cb.value;
                    bulkStatusHiddenInputs.appendChild(input);
                });
            }
        });
    }

    // Bulk Delete Form Submit
    if (bulkDeleteForm) {
        bulkDeleteForm.addEventListener('submit', function(e) {
            const checked = document.querySelectorAll('.order-checkbox:checked');
            if (checked.length === 0) {
                e.preventDefault();
                showToast('Please select at least one order to delete.', true);
                return;
            }

            const confirmMsg = 'DANGER: Are you sure you want to permanently delete ' + checked.length + ' selected order(s)?\nThis will also delete their items and cannot be undone.';
            if (!confirm(confirmMsg)) {
                e.preventDefault();
                return;
            }

            if (bulkDeleteHiddenInputs) {
                bulkDeleteHiddenInputs.innerHTML = '';
                checked.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'order_ids[]';
                    input.value = cb.value;
                    bulkDeleteHiddenInputs.appendChild(input);
                });
            }
        });
    }
});
</script>
@endpush
