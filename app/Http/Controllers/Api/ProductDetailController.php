<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CartManagement;
use App\Helpers\CartManagementDB;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductDetailController extends Controller
{
    public function show(Request $request, $slug)
    {
        // نجيب المنتج بالـ slug
        $product = Product::where('slug', $slug)->firstOrFail();

        // الكمية الافتراضية
        $quantity = 1;

        // لو المنتج موجود بالفعل في الكارت نجيب الكمية من هناك
        $item = CartManagement::getItemFromCartIfExist($product->id);
        if ($item !== null) {
            $quantity = $item;
        }

        return response()->json([
            'status' => 'success',
            'product' => $product,
            'quantity' => $quantity,
        ]);
    }

    public function addToCart(Request $request, $id)
        {
            if (!auth()->user()) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
            }

            $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);

            $quantity = $request->input('quantity', 1);
            $product  = Product::findOrFail($id);

            $cartItem = Cart::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->first();

            if ($cartItem) {
                $cartItem->quantity += $quantity;
                $cartItem->total_amount = $cartItem->unit_amount * $cartItem->quantity;
                $cartItem->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Product quantity updated in cart successfully!',
                    'total_cart_items' => Cart::where('user_id', auth()->id())->count(),
                ]);
            }

            Cart::create([
                'user_id'      => auth()->id(),
                'product_id'   => $product->id,
                'quantity'     => $quantity,
                'unit_amount'  => $product->price,
                'total_amount' => $product->price * $quantity,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Product added to cart successfully!',
                'total_cart_items' => Cart::where('user_id', auth()->id())->count(),
            ]);
        }

}
