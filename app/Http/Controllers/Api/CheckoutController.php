<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CartManagement;
use App\Helpers\CartManagementDB;
use App\Http\Controllers\Controller;
use App\Mail\OrderPlaced;
use App\Models\Addresses;
use App\Models\Order;
use App\Models\payment_methods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    // Checkout page data (cart items + payment methods)
    public function index(Request $request)
    {
        $cartItems = CartManagementDB::getCartItemsFromDB();

        if ($cartItems->count() == 0) {
            return response()->json([
                'message' => 'Cart is empty',
            ], 400);
        }

        $grandTotal = CartManagementDB::calculateGrandTotal($cartItems);
        $payment_methods = payment_methods::where('type', 'manual')->get();

        return response()->json([
            'cart_items' => $cartItems,
            'grand_total' => $grandTotal,
            'payment_methods' => $payment_methods
        ]);
    }

    // Place order
    public function placeOrder(Request $request)
    {

        try {
            $validated = $request->validate([
                'last_name'       => 'required|string|max:255',
                'first_name'      => 'required|string|min:4|max:255',
                'phone'           => 'required',
                'street_address'  => 'required|string',
                'city'            => 'required|string|max:255',
                'state'           => 'required|string|max:255',
                'zip_code'        => 'required',
                'payment_method'  => 'required|string',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors'  => $e->errors(),
            ], 422);
        }
        $cartItems = CartManagementDB::getCartItemsFromDB();


        if ($cartItems->count() == 0) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        // Stripe line items
        $lineItems = [];
        foreach ($cartItems as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => $item->unit_amount * 100,
                    'product_data' => [
                        'name' => $item->product->name,
                    ],
                ],
                'quantity' => $item->quantity,
            ];
        }

        // Create order
        $order = new Order();
        $order->user_id = $request->user()->id;
        $order->grand_total = CartManagementDB::calculateGrandTotal($cartItems);
        $order->payment_method = $request->payment_method;
        $order->payment_status = 'pending';
        $order->status = 'new';
        $order->currency = 'usd';
        $order->shipping_amount = 0;
        $order->shipping_method = 'none';
        $order->notes = 'Order placed by ' . $request->user()->name;
        $order->save();

        // Save address
        $address = new Addresses();
        $address->order_id = $order->id;
        $address->first_name = $request->first_name;
        $address->last_name = $request->last_name;
        $address->phone = $request->phone;
        $address->street_address = $request->street_address;
        $address->city = $request->city;
        $address->state = $request->state;
        $address->zip_code = $request->zip_code;
        $address->save();

        // Attach order items
        $order->items()->createMany(
            $cartItems->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_amount' => $item->unit_amount,
                    'total_amount' => $item->total_amount,
                ];
            })->toArray()
        );

        // Clear cart
        CartManagementDB::clearCartItemsFromDB();

        // Payment redirect URL
        $redirect_url = '';
        if ($request->payment_method == 'stripe') {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $sessionCheckout = Session::create([
                'payment_method_types' => ['card'],
                'customer_email' => $request->user()->email,
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('api.checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('cancel'),
            ]);
            $redirect_url = $sessionCheckout->url;
        } elseif ($request->payment_method == 'cod') {
            $redirect_url = route('api.checkout.success');
        } else {
            $paymentMethod = payment_methods::where('name', $request->payment_method)->first();
            $redirect_url = route('api.payment', [
                'payment_id' => $paymentMethod->id,
                'orderId' => $order->id,
            ]);
        }

        // ممكن تبعت إيميل تأكيد
        // Mail::to($request->user()->email)->send(new OrderPlaced($order));

        return response()->json([
            'message' => 'Order placed successfully',
            'order_id' => $order->id,
            'redirect_url' => $redirect_url
        ]);
    }
}
