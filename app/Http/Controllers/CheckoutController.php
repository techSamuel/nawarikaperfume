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

        \App\Jobs\SendFacebookCapiEvent::dispatch('InitiateCheckout', [
            'value' => $total,
            'currency' => 'BDT',
            'content_ids' => array_keys($cart),
            'content_type' => 'product',
            'num_items' => count($cart)
        ]);

        return view('checkout', compact('cartItems', 'subtotal', 'shipping', 'total', 'user'));
    }

    public function sidebar()
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

        if (count($cart) > 0) {
            \App\Jobs\SendFacebookCapiEvent::dispatch('InitiateCheckout', [
                'value' => $total,
                'currency' => 'BDT',
                'content_ids' => array_keys($cart),
                'content_type' => 'product',
                'num_items' => count($cart)
            ]);
        }

        return view('partials.checkout_sidebar', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }

    public function store(Request $request)
    {
        // Honeypot trap check for automated bots
        if ($request->filled('b_trap')) {
            return redirect()->route('home');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', 'regex:/^(?:\+88|88)?(01[3-9]\d{8})$/'],
            'address' => 'required|string|min:5|max:500',
        ], [
            'phone.regex' => 'Please enter a valid 11-digit Bangladeshi mobile number (e.g. 017XXXXXXXX).',
        ]);

        // Normalize phone number to clean 11 digits
        $cleanedPhone = preg_replace('/[^0-9]/', '', $request->phone);
        if (strlen($cleanedPhone) > 11 && str_starts_with($cleanedPhone, '8801')) {
            $cleanedPhone = substr($cleanedPhone, 2);
        }

        // Duplicate submission prevention (within 60 seconds)
        $recentOrder = Order::where('phone', $cleanedPhone)
            ->where('created_at', '>=', now()->subMinute())
            ->first();
        if ($recentOrder) {
            return redirect()->route('order.confirmation', $recentOrder->order_number);
        }

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
            $fbp = $request->cookie('_fbp');
            $fbc = $request->cookie('_fbc');
            if (!$fbc && (session('fbclid') || $request->query('fbclid'))) {
                $fbclid = $request->query('fbclid') ?? session('fbclid');
                $fbc = 'fb.1.' . (time() * 1000) . '.' . $fbclid;
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

            return redirect()->route('order.confirmation', $order->order_number)
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong. Please try again.');
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
