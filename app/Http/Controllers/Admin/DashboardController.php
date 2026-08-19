<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('status', '!=', 'cancelled')->sum('total'),
            'total_products' => Product::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'processing_orders' => Order::where('status', 'processing')->count(),
        ];

        $recentOrders = Order::with('user')->latest()->take(10)->get();
        $lowStockProducts = Product::where('stock', '<=', 5)->where('stock', '>', 0)->get();
        $outOfStockProducts = Product::where('stock', 0)->count();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStockProducts', 'outOfStockProducts'));
    }
}
