<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->inStock()->with('category');

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
            \App\Jobs\SendFacebookCapiEvent::dispatch('Search', ['search_string' => $search]);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $sort = $request->get('sort', 'latest');
        $query = match($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'name' => $query->orderBy('name', 'asc'),
            default => $query->orderBy('id', 'asc'),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::active()->withCount('products')->get();

        return view('shop', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->active()->with(['category', 'reviews' => function($q) {
            $q->where('is_approved', true)->latest();
        }])->firstOrFail();
        $relatedProducts = Product::active()->inStock()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)->get();

        \App\Jobs\SendFacebookCapiEvent::dispatch('ViewContent', [
            'content_name' => $product->name,
            'content_ids' => [$product->id],
            'content_type' => 'product',
            'value' => $product->sale_price ?? $product->price,
            'currency' => 'BDT'
        ]);

        return view('product', compact('product', 'relatedProducts'));
    }

    public function liveSearch(Request $request)
    {
        $q = trim($request->get('q', ''));
        $categorySlug = $request->get('category', '');

        if (empty($q) && empty($categorySlug)) {
            return response()->json(['results' => []]);
        }

        $query = Product::active()->with('category');

        if (!empty($categorySlug)) {
            $query->whereHas('category', fn($catQuery) => $catQuery->where('slug', $categorySlug));
        }

        if (!empty($q)) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$q}%"));
            });
        }

        $products = $query->orderBy('id', 'asc')->take(6)->get();

        $results = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->translated_name,
                'slug' => $product->slug,
                'url' => route('product.show', $product->slug),
                'price' => '৳' . number_format($product->display_price, 2),
                'original_price' => $product->isOnSale() ? '৳' . number_format($product->price, 2) : null,
                'discount_percent' => $product->isOnSale() ? $product->discount_percent : null,
                'image_url' => $product->image ? asset('storage/' . $product->image) : null,
                'category_name' => $product->category ? $product->category->translated_name : 'General',
                'in_stock' => $product->isInStock(),
            ];
        });

        return response()->json(['results' => $results]);
    }

    public function pingVisitorLog(Request $request)
    {
        $ip = $request->ip();
        $duration = max(1, min(60, (int)$request->input('duration', 10)));

        $latestLog = \App\Models\VisitorLog::where('ip_address', $ip)
            ->where('created_at', '>=', now()->subMinutes(15))
            ->latest('visited_at')
            ->first();

        if ($latestLog) {
            $latestLog->increment('duration_seconds', $duration);
            return response()->json(['success' => true, 'total_seconds' => $latestLog->duration_seconds]);
        }

        return response()->json(['success' => false], 404);
    }
}
