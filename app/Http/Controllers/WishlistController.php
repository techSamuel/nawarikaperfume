<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use App\Jobs\SendFacebookCapiEvent;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    private function getQuery()
    {
        if (auth()->check()) {
            return Wishlist::where('user_id', auth()->id());
        }

        return Wishlist::where('session_id', session()->getId());
    }

    public function index()
    {
        $wishlistItems = $this->getQuery()
            ->with('product.category')
            ->latest()
            ->get();

        return view('wishlist', compact('wishlistItems'));
    }

    public function toggle(Request $request, $productId)
    {
        $product = Product::active()->findOrFail($productId);
        $userId = auth()->id();
        $sessionId = session()->getId();

        $itemQuery = Wishlist::where('product_id', $product->id);
        if ($userId) {
            $itemQuery->where('user_id', $userId);
        } else {
            $itemQuery->where('session_id', $sessionId);
        }

        $existing = $itemQuery->first();

        if ($existing) {
            $existing->delete();
            $added = false;
            $message = 'Product removed from wishlist';
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $product->id,
            ]);
            $added = true;
            $message = 'Product added to wishlist!';

            // Dispatch Meta CAPI AddToWishlist event
            SendFacebookCapiEvent::send('AddToWishlist', [
                'content_name' => $product->name,
                'content_ids' => [(string) $product->id],
                'content_type' => 'product',
                'value' => (float) ($product->sale_price ?? $product->price),
                'currency' => 'BDT',
            ]);
        }

        $count = $this->getQuery()->count();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'added' => $added,
                'count' => $count,
                'message' => $message,
                'product' => [
                    'id' => (string) $product->id,
                    'name' => $product->name,
                    'price' => (float) ($product->sale_price ?? $product->price),
                ]
            ]);
        }

        return back()->with('success', $message);
    }

    public function remove($productId)
    {
        $this->getQuery()->where('product_id', $productId)->delete();
        $count = $this->getQuery()->count();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'count' => $count,
                'message' => 'Removed from wishlist'
            ]);
        }

        return back()->with('success', 'Product removed from wishlist');
    }

    public function count()
    {
        return response()->json([
            'count' => $this->getQuery()->count()
        ]);
    }
}
