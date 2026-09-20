<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::active()->featured()->inStock()->with('category')->latest()->take(8)->get();
        $categories = Category::active()->withCount('products')->get();
        $newArrivals = Product::active()->inStock()->with('category')->latest()->take(4)->get();
        return view('home', compact('featuredProducts', 'categories', 'newArrivals'));
    }

    public function trackPage()
    {
        return view('track');
    }

    public function trackOrder(Request $request)
    {
        $query = trim($request->input('query', ''));

        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter your Order Number or Phone Number.'
            ], 400);
        }

        $cleanQuery = preg_replace('/[^\w\-]/', '', $query);

        $orders = Order::with('items')
            ->where(function ($q) use ($query, $cleanQuery) {
                $q->where('order_number', 'like', "%{$query}%")
                  ->orWhere('phone', 'like', "%{$query}%")
                  ->orWhere('phone', 'like', "%{$cleanQuery}%");
            })
            ->latest()
            ->take(5)
            ->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No order found for "' . e($query) . '". Please check your Phone Number or Order Number.'
            ]);
        }

        $formattedOrders = $orders->map(function ($order) {
            return [
                'order_number' => $order->order_number,
                'status' => $order->status,
                'status_badge' => $order->status_badge,
                'status_label' => ucfirst($order->status),
                'date' => $order->created_at->format('M d, Y h:i A'),
                'customer_name' => $order->name,
                'phone' => strlen($order->phone) > 6 ? substr($order->phone, 0, 4) . '***' . substr($order->phone, -3) : $order->phone,
                'total' => '৳' . number_format($order->total, 2),
                'payment_method' => 'Cash on Delivery',
                'items_count' => $order->items->count(),
                'items' => $order->items->map(function ($item) {
                    return [
                        'name' => $item->product_name,
                        'qty' => $item->quantity,
                        'price' => '৳' . number_format($item->price, 2),
                        'total' => '৳' . number_format($item->price * $item->quantity, 2),
                    ];
                }),
                'step' => match($order->status) {
                    'pending' => 1,
                    'processing' => 2,
                    'shipped' => 3,
                    'delivered' => 4,
                    'cancelled' => 0,
                    default => 1,
                }
            ];
        });

        return response()->json([
            'success' => true,
            'orders' => $formattedOrders,
        ]);
    }

    public function switchLanguage($locale)
    {
        if (in_array($locale, ['en', 'bn'])) {
            session()->put('app_locale', $locale);
            cookie()->queue('app_locale', $locale, 60 * 24 * 365);
        }
        return back();
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        \App\Models\ContactMessage::create($validated);

        return redirect()->back()->with('success', 'Your message has been sent successfully. We will get back to you soon.');
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function shippingPolicy()
    {
        return view('pages.shipping_policy');
    }

    public function returnPolicy()
    {
        return view('pages.return_policy');
    }
}
