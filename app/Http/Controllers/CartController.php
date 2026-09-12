<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
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

        $shipping = $subtotal > 0 ? ($subtotal >= 500 ? 0 : 50) : 0;
        $total = $subtotal + $shipping;

        return view('cart', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if (!$product->isInStock()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Product is out of stock.'], 400);
            }
            return back()->with('error', 'Product is out of stock.');
        }

        $cart = session()->get('cart', []);
        $qty = $request->input('quantity', 1);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $qty;
        } else {
            $cart[$id] = ['quantity' => $qty];
        }

        if ($cart[$id]['quantity'] > $product->stock) {
            $cart[$id]['quantity'] = $product->stock;
        }

        session()->put('cart', $cart);

        \App\Jobs\SendFacebookCapiEvent::dispatch('AddToCart', [
            'content_name' => $product->name,
            'content_ids' => [$product->id],
            'content_type' => 'product',
            'value' => ($product->sale_price ?? $product->price) * $qty,
            'currency' => 'BDT',
            'num_items' => $qty
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Product added to cart!']);
        }

        return back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        $product = Product::find($id);

        if (isset($cart[$id]) && $product) {
            $qty = max(1, (int)$request->input('quantity', 1));
            $cart[$id]['quantity'] = min($qty, $product->stock);
            session()->put('cart', $cart);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Cart updated!');
    }

    public function remove(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        unset($cart[$id]);
        session()->put('cart', $cart);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Cart cleared.');
    }

    public function count()
    {
        $cart = session()->get('cart', []);
        $count = array_sum(array_column($cart, 'quantity'));
        return response()->json(['count' => $count]);
    }
}
