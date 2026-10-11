<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->latest()->paginate(15)->withQueryString();
        $statusCounts = [
            'all' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'confirm' => Order::where('status', 'confirm')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'user');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,confirm,shipped,delivered,cancelled',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        if ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            // Dispatch Meta CAPI CancelOrder event
            $order->load('items');
            $contents = [];
            foreach ($order->items as $item) {
                $contents[] = [
                    'id' => (string) $item->product_id,
                    'quantity' => (int) $item->quantity,
                    'item_price' => (float) $item->price,
                ];
            }

            \App\Jobs\SendFacebookCapiEvent::send(
                'CancelOrder',
                [
                    'value' => (float) $order->total,
                    'currency' => 'BDT',
                    'content_ids' => $order->items->pluck('product_id')->map(fn($id) => (string) $id)->values()->toArray(),
                    'contents' => $contents,
                    'content_type' => 'product',
                    'num_items' => $order->items->sum('quantity'),
                    'order_id' => $order->order_number,
                    'status' => 'cancelled',
                ],
                [
                    'phone' => $order->phone,
                    'email' => $order->email,
                    'name' => $order->name,
                    'city' => $order->city,
                    'ip_address' => $order->ip_address,
                    'user_agent' => $order->user_agent,
                    'fbp' => $order->fbp,
                    'fbc' => $order->fbc,
                    'event_source_url' => route('order.confirmation', $order->order_number),
                ],
                $order->order_number . '_cancel',
                time()
            );
        }

        if ($request->status === 'confirm' && $oldStatus !== 'confirm') {
            if (!$order->capi_purchase_sent_at) {
                $order->load('items');

                $contents = [];
                foreach ($order->items as $item) {
                    $contents[] = [
                        'id' => (string) $item->product_id,
                        'quantity' => (int) $item->quantity,
                        'item_price' => (float) $item->price,
                    ];
                }

                \App\Jobs\SendFacebookCapiEvent::dispatchSync(
                    'Purchase',
                    [
                        'value' => (float) $order->total,
                        'currency' => 'BDT',
                        'content_ids' => $order->items->pluck('product_id')->map(fn($id) => (string) $id)->values()->toArray(),
                        'contents' => $contents,
                        'content_type' => 'product',
                        'num_items' => $order->items->sum('quantity'),
                        'order_id' => $order->order_number,
                    ],
                    [
                        'phone' => $order->phone,
                        'email' => $order->email,
                        'name' => $order->name,
                        'city' => $order->city,
                        'ip_address' => $order->ip_address,
                        'user_agent' => $order->user_agent,
                        'fbp' => $order->fbp,
                        'fbc' => $order->fbc,
                        'event_source_url' => route('order.confirmation', $order->order_number),
                    ],
                    $order->order_number,
                    time()
                );

                $order->update(['capi_purchase_sent_at' => now()]);
            }
        }

        return back()->with('success', 'Order status updated to ' . ucfirst($request->status) . '.');
    }

    public function destroy(Order $order)
    {
        $orderNumber = $order->order_number;
        $order->items()->delete();
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Order #' . $orderNumber . ' has been deleted successfully.');
    }
}
