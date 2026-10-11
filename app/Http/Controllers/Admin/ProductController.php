<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'delivery_charge' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'videos.*' => 'nullable|mimes:mp4,mov,ogg,qt|max:20000',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $data = $request->only(['name', 'category_id', 'description', 'price', 'sale_price', 'delivery_charge', 'stock']);
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');

        $count = Product::where('slug', $data['slug'])->count();
        if ($count > 0) {
            $data['slug'] .= '-' . ($count + 1);
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        if ($request->hasFile('gallery')) {
            $galleryPaths = [];
            foreach ($request->file('gallery') as $file) {
                $galleryPaths[] = $file->store('products/gallery', 'public');
            }
            $data['gallery'] = $galleryPaths;
        }

        if ($request->hasFile('videos')) {
            $videoPaths = [];
            foreach ($request->file('videos') as $file) {
                $videoPaths[] = $file->store('products/videos', 'public');
            }
            $data['videos'] = $videoPaths;
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::active()->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'delivery_charge' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'videos.*' => 'nullable|mimes:mp4,mov,ogg,qt|max:20000',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $data = $request->only(['name', 'category_id', 'description', 'price', 'sale_price', 'delivery_charge', 'stock']);
        $data['is_active'] = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');

        if (!$request->filled('sale_price')) {
            $data['sale_price'] = null;
        }

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        if ($request->hasFile('gallery')) {
            $galleryPaths = $product->gallery ?? [];
            foreach ($request->file('gallery') as $file) {
                $galleryPaths[] = $file->store('products/gallery', 'public');
            }
            $data['gallery'] = $galleryPaths;
        }

        if ($request->hasFile('videos')) {
            $videoPaths = $product->videos ?? [];
            foreach ($request->file('videos') as $file) {
                $videoPaths[] = $file->store('products/videos', 'public');
            }
            $data['videos'] = $videoPaths;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    /**
     * Display the Product Organizer page to organize product serials.
     */
    public function organizer(Request $request)
    {
        $scope = $request->input('scope', 'featured');
        $selectedCategoryId = $request->input('category_id');

        $categories = Category::active()->withCount('products')->orderBy('name')->get();
        $featuredCount = Product::featured()->count();
        $totalProducts = Product::count();

        $query = Product::with('category');

        if ($scope === 'featured') {
            $products = $query->featured()
                ->orderBy('featured_order', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        } elseif ($scope === 'category') {
            if ($selectedCategoryId) {
                $query->where('category_id', $selectedCategoryId);
            } elseif ($categories->isNotEmpty()) {
                // Default to first category if none selected
                $selectedCategoryId = $categories->first()->id;
                $query->where('category_id', $selectedCategoryId);
            }
            $products = $query->orderBy('sort_order', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        } else {
            // All products
            $scope = 'all';
            if ($selectedCategoryId) {
                $query->where('category_id', $selectedCategoryId);
            }
            $products = $query->orderBy('sort_order', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        }

        return view('admin.products.organizer', compact(
            'products',
            'categories',
            'scope',
            'selectedCategoryId',
            'featuredCount',
            'totalProducts'
        ));
    }

    /**
     * Update the serial / sort orders for products via AJAX.
     */
    public function updateOrder(Request $request)
    {
        $scope = $request->input('scope', 'featured');
        $orders = $request->input('orders', []);

        if (empty($orders) || !is_array($orders)) {
            return response()->json(['success' => false, 'message' => 'No order data received.'], 400);
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($scope, $orders) {
            foreach ($orders as $key => $item) {
                $id = is_array($item) ? ($item['id'] ?? null) : $key;
                $order = is_array($item) ? ($item['order'] ?? 0) : $item;

                if ($id) {
                    if ($scope === 'featured') {
                        Product::where('id', $id)->update(['featured_order' => (int) $order]);
                    } else {
                        Product::where('id', $id)->update(['sort_order' => (int) $order]);
                    }
                }
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Product serial orders updated successfully!'
        ]);
    }

    /**
     * Toggle a product's featured status from the organizer.
     */
    public function toggleFeatured(Request $request, Product $product)
    {
        $newStatus = !$product->is_featured;
        $data = ['is_featured' => $newStatus];

        if ($newStatus && ($product->featured_order === 0 || is_null($product->featured_order))) {
            $data['featured_order'] = (Product::max('featured_order') ?? 0) + 1;
        }

        $product->update($data);

        return response()->json([
            'success' => true,
            'is_featured' => $newStatus,
            'message' => $newStatus ? 'Product marked as Featured.' : 'Product removed from Featured.'
        ]);
    }
}
