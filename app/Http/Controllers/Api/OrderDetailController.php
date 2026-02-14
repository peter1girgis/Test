<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Addresses;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;


// it`s unuse now
class OrderDetailController extends Controller
{
    public function show($order_id)
    {
        $order = Order::findOrFail($order_id);
        $orderItems = OrderItem::with('product')->where('order_id', $order_id)->get();
        $address = Addresses::where('order_id', $order_id)->first();

        return response()->json([
            'status' => 'success',
            'order' => $order,
            'items' => $orderItems,
            'address' => $address,
        ]);
    }
}

