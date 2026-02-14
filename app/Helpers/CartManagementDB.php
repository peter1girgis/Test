<?php

namespace App\Helpers;

use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartManagementDB {

    // Add item to cart
    static public function addItemToCart($product_id) {
        $user_id = Auth::id();
        $cartItem = Cart::where('user_id', $user_id)
                        ->where('product_id', $product_id)
                        ->first();

        if ($cartItem) {
            $cartItem->quantity++;
            $cartItem->total_amount = $cartItem->unit_amount * $cartItem->quantity;
            $cartItem->save();
        } else {
            $product = Product::findOrFail($product_id);
            Cart::create([
                'user_id' => $user_id,
                'product_id' => $product->id,
                'quantity' => 1,
                'unit_amount' => $product->price,
                'total_amount' => $product->price,
            ]);
        }

        return Cart::where('user_id', $user_id)->count();
    }

    // Add item with Qty
    static public function addItemToCartWithQty($product_id, $qty) {
        $user_id = Auth::id();
        $cartItem = Cart::where('user_id', $user_id)
                        ->where('product_id', $product_id)
                        ->first();

        if ($cartItem) {
            $cartItem->quantity += $qty;
            $cartItem->total_amount = $cartItem->unit_amount * $cartItem->quantity;
            $cartItem->save();
        } else {
            $product = Product::findOrFail($product_id);
            Cart::create([
                'user_id' => $user_id,
                'product_id' => $product->id,
                'quantity' => $qty,
                'unit_amount' => $product->price,
                'total_amount' => $product->price * $qty,
            ]);
        }

        return Cart::where('user_id', $user_id)->count();
    }

    // Check if item exists in cart
    static public function getItemFromCartIfExist($product_id) {
        $user_id = Auth::id();
        $cartItem = Cart::where('user_id', $user_id)
                        ->where('product_id', $product_id)
                        ->first();
        return $cartItem ? $cartItem->quantity : null;
    }

    // Remove item
    static public function removeCartItem($product_id) {
        $user_id = Auth::id();
        Cart::where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->delete();

        return self::getCartItemsFromDB();
    }

    // Get all items
    static public function getCartItemsFromDB() {
        $user_id = Auth::id();
        return Cart::where('user_id', $user_id)->with('product')->get();
    }

    // Clear cart
    static public function clearCartItemsFromDB() {
        $user_id = Auth::id();
        Cart::where('user_id', $user_id)->delete();
    }

    // Increment qty
    static public function incrementQuantityToCartItem($product_id) {
        $user_id = Auth::id();
        $cartItem = Cart::where('user_id', $user_id)
                        ->where('product_id', $product_id)
                        ->first();

        if ($cartItem) {
            $cartItem->quantity++;
            $cartItem->total_amount = $cartItem->unit_amount * $cartItem->quantity;
            $cartItem->save();
        }

        return self::getCartItemsFromDB();
    }

    // Decrement qty
    static public function decrementQuantityFromCartItem($product_id) {
        $user_id = Auth::id();
        $cartItem = Cart::where('user_id', $user_id)
                        ->where('product_id', $product_id)
                        ->first();

        if ($cartItem && $cartItem->quantity > 1) {
            $cartItem->quantity--;
            $cartItem->total_amount = $cartItem->unit_amount * $cartItem->quantity;
            $cartItem->save();
        }

        return self::getCartItemsFromDB();
    }

    // Grand total
    static public function calculateGrandTotal($items) {
        return $items->sum('total_amount');
    }
}
