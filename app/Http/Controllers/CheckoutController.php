<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
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

        $shipping = $subtotal >= 500 ? 0 : 50;
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

        $shipping = $subtotal > 0 ? ($subtotal >= 500 ? 0 : 50) : 0;
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
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

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

            $shipping = $subtotal >= 500 ? 0 : 50;
            $total = $subtotal + $shipping;

            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => Order::generateOrderNumber(),
                'name' => $request->name,
                'email' => null,
                'phone' => $request->phone,
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
            ]);

            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            DB::commit();
            session()->forget('cart');

            \App\Jobs\SendFacebookCapiEvent::dispatch('Purchase', [
                'value' => $total,
                'currency' => 'BDT',
                'content_ids' => array_column($orderItems, 'product_id'),
                'content_type' => 'product',
                'order_id' => $order->order_number
            ]);

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
