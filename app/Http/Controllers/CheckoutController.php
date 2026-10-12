<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    use \App\Traits\VisitorDetectionTrait;

    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if ($product) {
                $price = $product->sale_price ?? $product->price;
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                    'total' => $price * $item['quantity'],
                ];
                $subtotal += $price * $item['quantity'];
            }
        }

        $shipping = $subtotal > 0 ? \App\Services\CartService::calculateShipping($cart, $subtotal) : 0;
        $total = $subtotal + $shipping;
        $user = auth()->user();

        // Unique event ID for 1-to-1 Pixel & CAPI deduplication
        $eventId = 'ic_' . uniqid() . '_' . bin2hex(random_bytes(4));

        $contents = [];
        foreach ($cartItems as $ci) {
            $contents[] = [
                'id' => (string) $ci['product']->id,
                'quantity' => (int) $ci['quantity'],
                'item_price' => (float) $ci['price'],
            ];
        }

        \App\Jobs\SendFacebookCapiEvent::send('InitiateCheckout', [
            'value' => (float) $total,
            'currency' => 'BDT',
            'content_ids' => array_values(array_map('strval', array_keys($cart))),
            'contents' => $contents,
            'content_type' => 'product',
            'num_items' => (int) count($cart),
        ], [], $eventId);

        return view('checkout', compact('cartItems', 'subtotal', 'shipping', 'total', 'user', 'eventId'));
    }

    public function sidebar(Request $request)
    {
        $cart = session()->get('cart', []);
        
        $cartItems = [];
        $subtotal = 0;

        if (!empty($cart)) {
            foreach ($cart as $id => $item) {
                $product = Product::find($id);
                if ($product) {
                    $price = $product->sale_price ?? $product->price;
                    $cartItems[] = [
                        'product' => $product,
                        'quantity' => $item['quantity'],
                        'price' => $price,
                        'total' => $price * $item['quantity'],
                    ];
                    $subtotal += $price * $item['quantity'];
                }
            }
        }

        $shipping = $subtotal > 0 ? \App\Services\CartService::calculateShipping($cart, $subtotal) : 0;
        $total = $subtotal + $shipping;

        $eventId = null;
        $isRefresh = $request->boolean('refresh');

        // Only fire InitiateCheckout on genuine checkout opening, not on quantity +/- adjustments
        if (count($cart) > 0 && !$isRefresh) {
            $eventId = 'ic_' . uniqid() . '_' . bin2hex(random_bytes(4));

            $contents = [];
            foreach ($cartItems as $ci) {
                $contents[] = [
                    'id' => (string) $ci['product']->id,
                    'quantity' => (int) $ci['quantity'],
                    'item_price' => (float) $ci['price'],
                ];
            }

            \App\Jobs\SendFacebookCapiEvent::send('InitiateCheckout', [
                'value' => (float) $total,
                'currency' => 'BDT',
                'content_ids' => array_values(array_map('strval', array_keys($cart))),
                'contents' => $contents,
                'content_type' => 'product',
                'num_items' => (int) count($cart),
            ], [], $eventId);
        }

        return view('partials.checkout_sidebar', compact('cartItems', 'subtotal', 'shipping', 'total', 'eventId'));
    }

    public function store(Request $request)
    {
        // Normalize phone number by stripping spaces, dashes, brackets, etc.
        $rawPhone = preg_replace('/[^0-9]/', '', (string)$request->phone);
        if (str_starts_with($rawPhone, '8801') && strlen($rawPhone) > 11) {
            $rawPhone = substr($rawPhone, 2);
        }
        $request->merge(['phone' => $rawPhone]);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|min:10|max:15',
            'address' => 'required|string|max:500',
        ]);

        $cleanedPhone = $rawPhone;

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        try {
            DB::beginTransaction();

            $subtotal = 0;
            $orderItems = [];

            foreach ($cart as $id => $item) {
                $product = Product::find($id);
                if (!$product || $product->stock < $item['quantity']) {
                    DB::rollBack();
                    return back()->with('error', "Product '{$product->name}' is out of stock or insufficient quantity.");
                }

                $price = $product->sale_price ?? $product->price;
                $lineTotal = $price * $item['quantity'];
                $subtotal += $lineTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $price,
                    'quantity' => $item['quantity'],
                ];

                $product->decrement('stock', $item['quantity']);
            }

            $shipping = $subtotal > 0 ? \App\Services\CartService::calculateShipping($cart, $subtotal) : 0;
            $total = $subtotal + $shipping;

            $userAgent = $request->header('User-Agent', '');
            $timezone = $request->header('X-Timezone') ?? $request->cookie('visitor_tz') ?? 'Asia/Dhaka';

            // Capture Meta Pixel Browser ID (_fbp) and Click ID (_fbc)
            $fbp = $request->input('fbp') ?? $request->cookie('_fbp') ?? session('fbp');
            $fbc = $request->input('fbc') ?? $request->cookie('_fbc') ?? session('fbc');
            if (!$fbc && ($request->input('fbclid') ?? $request->query('fbclid') ?? session('fbclid'))) {
                $fbclid = $request->input('fbclid') ?? $request->query('fbclid') ?? session('fbclid');
                $fbc = 'fb.1.' . ((int) round(microtime(true) * 1000)) . '.' . trim($fbclid);
                session(['fbc' => $fbc]);
            }

            $userEmail = auth()->user()?->email ?? $request->email ?? null;

            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => Order::generateOrderNumber(),
                'name' => $request->name,
                'email' => $userEmail,
                'phone' => $cleanedPhone,
                'address' => $request->address,
                'city' => null,
                'state' => null,
                'zip' => null,
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'total' => $total,
                'status' => 'pending',
                'payment_method' => 'cod',
                'notes' => $request->notes,
                'ip_address' => $request->ip(),
                'user_agent' => substr($userAgent, 0, 500),
                'device_type' => $this->detectDevice($userAgent),
                'browser' => $this->detectBrowser($userAgent),
                'platform' => $this->detectPlatform($userAgent),
                'timezone' => $timezone,
                'country' => $this->detectCountry($request),
                'fbp' => $fbp,
                'fbc' => $fbc,
            ]);

            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            DB::commit();
            session()->forget('cart');

            // Dispatch Email Notifications (Admin alert + Customer confirmation)
            $mailService = app(\App\Services\MailConfigService::class);
            $mailService->sendAdminNewOrderNotification($order);
            $mailService->sendCustomerNewOrderNotification($order);

            return redirect()->route('order.confirmation', $order->order_number)
                ->with('success', 'Order placed successfully!');

        } catch (\Throwable $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Order checkout exception: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function confirmation($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->with('items')->firstOrFail();

        if (auth()->check() && $order->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        return view('order-confirmation', compact('order'));
    }
}
