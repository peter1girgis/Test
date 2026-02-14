<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CartManagementDB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Get all cart items
    public function index(Request $request)
    {
        $cartItems = CartManagementDB::getCartItemsFromDB();
        $grandTotal = CartManagementDB::calculateGrandTotal($cartItems);

        return response()->json([
            'items' => $cartItems,
            'grand_total' => $grandTotal,
            'count' => $cartItems->count()
        ]);
    }

    // Add item to cart
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'nullable|integer|min:1'
        ]);

        if ($request->quantity) {
            CartManagementDB::addItemToCartWithQty($validated['product_id'], $validated['quantity']);
        } else {
            CartManagementDB::addItemToCart($validated['product_id']);
        }

        return response()->json(['message' => 'Item added to cart successfully']);
    }

    // Remove item from cart
    public function destroy(Request $request, $product_id)
    {
        CartManagementDB::removeCartItem($product_id);

        $cartItems = CartManagementDB::getCartItemsFromDB();
        $grandTotal = CartManagementDB::calculateGrandTotal($cartItems);

        return response()->json([
            'message' => 'Item removed successfully',
            'items' => $cartItems,
            'grand_total' => $grandTotal,
            'count' => $cartItems->count()
        ]);
    }

    // Increase quantity
    public function increaseQty(Request $request, $product_id)
    {
        $cartItems = CartManagementDB::incrementQuantityToCartItem($product_id);
        $grandTotal = CartManagementDB::calculateGrandTotal($cartItems);

        return response()->json([
            'items' => $cartItems,
            'grand_total' => $grandTotal,
            'count' => $cartItems->count()
        ]);
    }

    // Decrease quantity
    public function decreaseQty(Request $request, $product_id)
    {
        $cartItems = CartManagementDB::decrementQuantityFromCartItem($product_id);
        $grandTotal = CartManagementDB::calculateGrandTotal($cartItems);

        return response()->json([
            'items' => $cartItems,
            'grand_total' => $grandTotal,
            'count' => $cartItems->count()
        ]);
    }

    // Clear cart
    public function clear(Request $request)
    {
        CartManagementDB::clearCartItemsFromDB();

        return response()->json([
            'message' => 'Cart cleared successfully',
            'items' => [],
            'grand_total' => 0,
            'count' => 0
        ]);
    }
}
