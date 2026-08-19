<?php

namespace App\Http\Controllers;

use App\Models\Order;

class AccountController extends Controller
{
    public function orders()
    {
        $orders = auth()->user()->orders()->latest()->paginate(10);
        return view('account.orders', compact('orders'));
    }

    public function orderDetail($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->with('items.product')
            ->firstOrFail();

        return view('account.order-detail', compact('order'));
    }
}
