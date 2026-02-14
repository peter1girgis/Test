<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class SuccessController extends Controller
{
    public function success(Request $request)
    {
        $user = $request->user();
        $sessionId = $request->query('session_id'); // ?session_id=xxxx

        $latestOrder = Order::with('address')
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        if (!$latestOrder) {
            return response()->json([
                'message' => 'No order found for this user'
            ], 404);
        }

        // تحقق من Stripe لو في session_id
        if ($sessionId) {
            Stripe::setApiKey(env('STRIPE_SECRET')); // ✅ خلي بالك من اسم المتغير

            $sessionInfo = Session::retrieve($sessionId);

            if ($sessionInfo->payment_status !== 'paid') {
                $latestOrder->payment_status = 'failed';
            } else {
                $latestOrder->payment_status = 'paid';
            }
            $latestOrder->save();
        }

        return response()->json([
            'message' => 'Order retrieved successfully',
            'order' => $latestOrder
        ]);
    }
}
