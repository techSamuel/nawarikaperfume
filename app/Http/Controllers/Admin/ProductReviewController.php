<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductReview;
use App\Services\GeminiService;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'customer_name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string',
        ]);

        ProductReview::create([
            'product_id' => $request->product_id,
            'customer_name' => $request->customer_name,
            'rating' => $request->rating,
            'review_text' => $request->review_text,
            'is_approved' => true,
        ]);

        return back()->with('success', 'Review added successfully.');
    }

    public function destroy(ProductReview $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted successfully.');
    }

    /**
     * Generate AI customer reviews for a product using Gemini.
     */
    public function generateAiReviews(Request $request, Product $product, GeminiService $gemini)
    {
        $count = (int) $request->input('count', 3);
        $options = [
            'rating_style' => $request->input('rating_style', 'mostly_5'),
            'language' => $request->input('language', 'mixed'),
            'custom_notes' => $request->input('custom_notes', ''),
        ];

        try {
            $reviews = $gemini->generateReviews($product, $count, $options);

            // If auto-save was requested directly
            if ($request->boolean('auto_save')) {
                $savedReviews = [];
                foreach ($reviews as $item) {
                    $saved = ProductReview::create([
                        'product_id' => $product->id,
                        'customer_name' => $item['customer_name'],
                        'rating' => $item['rating'],
                        'review_text' => $item['review_text'],
                        'is_approved' => true,
                    ]);
                    $savedReviews[] = $saved;
                }

                return response()->json([
                    'success' => true,
                    'auto_saved' => true,
                    'count' => count($savedReviews),
                    'reviews' => $savedReviews,
                    'message' => count($savedReviews) . ' AI reviews generated and saved successfully!',
                ]);
            }

            return response()->json([
                'success' => true,
                'auto_saved' => false,
                'count' => count($reviews),
                'reviews' => $reviews,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Save generated AI reviews to a product.
     */
    public function saveAiReviews(Request $request, Product $product)
    {
        $request->validate([
            'reviews' => 'required|array|min:1',
            'reviews.*.customer_name' => 'required|string|max:255',
            'reviews.*.rating' => 'required|integer|min:1|max:5',
            'reviews.*.review_text' => 'required|string',
        ]);

        $created = [];
        foreach ($request->reviews as $item) {
            $created[] = ProductReview::create([
                'product_id' => $product->id,
                'customer_name' => $item['customer_name'],
                'rating' => (int) $item['rating'],
                'review_text' => $item['review_text'],
                'is_approved' => true,
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'count' => count($created),
                'reviews' => $created,
                'message' => count($created) . ' review(s) added successfully.',
            ]);
        }

        return back()->with('success', count($created) . ' review(s) added successfully.');
    }
}
