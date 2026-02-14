<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // نجيب الطلبات بتاعت اليوزر الحالي
        $orders = Order::with('address') // لو عندك علاقة address
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'orders' => $orders
        ]);
    }

    public function show(Request $request, $id)
    {
        $order = Order::with('address', 'Items.product')
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'order' => $order
        ]);
    }
}
