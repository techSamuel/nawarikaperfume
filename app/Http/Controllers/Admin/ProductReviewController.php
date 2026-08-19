<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
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
}
