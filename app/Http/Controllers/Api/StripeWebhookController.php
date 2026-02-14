<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\CartManagement;
use App\Models\Order;
use App\Models\Addresses;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeWebhookController extends Controller
{
    public function placeOrder(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'phone'      => 'required|string',
            'street_address' => 'required|string',
            'city'       => 'required|string',
            'state'      => 'required|string',
            'zip_code'   => 'required|string',
            'payment_method' => 'required|string|in:stripe,cod',
            'cart_items' => 'required|array',
        ]);

        $cartItems = $data['cart_items']; // جايه من الـ frontend
        $lineItems = [];

        foreach ($cartItems as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => $item['unit_amount'] * 100,
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                ],
                'quantity' => $item['quantity'],
            ];
        }

        // إنشاء الطلب
        $order = new Order();
        $order->user_id = auth('sanctum')->id(); // لو مستخدم مسجل
        $order->grand_total = collect($cartItems)->sum(fn($i) => $i['unit_amount'] * $i['quantity']);
        $order->payment_method = $data['payment_method'];
        $order->payment_status = 'pending';
        $order->status = 'new';
        $order->currency = 'usd';
        $order->shipping_amount = 0;
        $order->shipping_method = 'none';
        $order->save();

        // العنوان
        $address = new Addresses($data);
        $address->order_id = $order->id;
        $address->save();

        // حفظ العناصر
        $order->items()->createMany($cartItems);

        // لو Stripe
        $redirectUrl = null;
        if ($data['payment_method'] === 'stripe') {
            Stripe::setApiKey(env('STIPE_SECRET'));
            $session = Session::create([
                'payment_method_types' => ['card'],
                'customer_email' => auth('sanctum')->user()?->email,
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => url('/api/checkout/success?session_id={CHECKOUT_SESSION_ID}'),
                'cancel_url' => url('/api/payment/cancel'),
            ]);
            $redirectUrl = $session->url;
        }

        return response()->json([
            'order_id' => $order->id,
            'redirect_url' => $redirectUrl,
        ]);
    }
}

