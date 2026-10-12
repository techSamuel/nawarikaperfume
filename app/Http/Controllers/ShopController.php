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

        $searchEventId = null;
        if ($request->filled('search')) {
            $search = $request->search;
            $searchEventId = 'srch_' . bin2hex(random_bytes(6));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
            \App\Jobs\SendFacebookCapiEvent::send('Search', ['search_string' => $search], [], $searchEventId);
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
            default => $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc'),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::active()->withCount('products')->get();

        return view('shop', compact('products', 'categories', 'searchEventId'));
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

        $eventId = 'vc_' . $product->id . '_' . bin2hex(random_bytes(6));

        \App\Jobs\SendFacebookCapiEvent::send('ViewContent', [
            'content_name' => $product->name,
            'content_ids' => [(string) $product->id],
            'content_type' => 'product',
            'value' => (float) ($product->sale_price ?? $product->price),
            'currency' => 'BDT'
        ], [], $eventId);

        return view('product', compact('product', 'relatedProducts', 'eventId'));
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

    public function facebookCatalogFeed()
    {
        $products = Product::active()->with('category')->get();
        $settings = \Illuminate\Support\Facades\Cache::remember('site_settings', 60*24, function () {
            return \App\Models\Setting::pluck('value', 'key')->toArray();
        });
        $siteName = $settings['site_name'] ?? 'LUXE Perfumes';
        $siteUrl = config('app.url');

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rss version="2.0" xmlns:g="http://base.google.com/ns/1.0"></rss>');
        $channel = $xml->addChild('channel');
        $channel->addChild('title', htmlspecialchars($siteName));
        $channel->addChild('link', htmlspecialchars($siteUrl));
        $channel->addChild('description', htmlspecialchars($siteName . ' Product Catalog Feed for Meta Commerce Manager'));

        foreach ($products as $product) {
            $item = $channel->addChild('item');
            $item->addChild('g:id', (string) $product->id, 'http://base.google.com/ns/1.0');
            $item->addChild('g:title', htmlspecialchars($product->name), 'http://base.google.com/ns/1.0');
            $desc = strip_tags($product->description ?? $product->name);
            $item->addChild('g:description', htmlspecialchars(substr($desc, 0, 5000)), 'http://base.google.com/ns/1.0');
            $item->addChild('g:link', htmlspecialchars(route('product.show', $product->slug)), 'http://base.google.com/ns/1.0');
            
            $imgUrl = $product->image ? asset('uploads/' . $product->image) : asset('images/placeholder.jpg');
            $item->addChild('g:image_link', htmlspecialchars($imgUrl), 'http://base.google.com/ns/1.0');
            $item->addChild('g:brand', htmlspecialchars($siteName), 'http://base.google.com/ns/1.0');
            $item->addChild('g:condition', 'new', 'http://base.google.com/ns/1.0');
            $item->addChild('g:availability', $product->isInStock() ? 'in stock' : 'out of stock', 'http://base.google.com/ns/1.0');
            $item->addChild('g:price', number_format((float)$product->price, 2, '.', '') . ' BDT', 'http://base.google.com/ns/1.0');

            if ($product->isOnSale()) {
                $item->addChild('g:sale_price', number_format((float)$product->sale_price, 2, '.', '') . ' BDT', 'http://base.google.com/ns/1.0');
            }

            if ($product->category) {
                $item->addChild('g:product_type', htmlspecialchars($product->category->name), 'http://base.google.com/ns/1.0');
            }
        }

        return response($xml->asXML(), 200, [
            'Content-Type' => 'application/xml; charset=utf-8'
        ]);
    }
}
