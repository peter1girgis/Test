<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Cookie;

class CartManagement {
    // static public function

    // add item to cart

    static public function addItemToCart($product_id){
        $cartItems = self::getCartItemsFromCookie();
        $existing_item = null ;
        foreach($cartItems as $key => $item){
            if($item['product_id'] == $product_id){
                $existing_item = $key ;
                break ;
            }
        }
        if($existing_item !== null){
            $cartItems[$existing_item]['quantity']++ ;
            $cartItems[$existing_item]['total_amount'] = $cartItems[$existing_item]['unit_amount'] * $cartItems[$existing_item]['quantity'];
        }
        else {
            $product = Product::where('id',$product_id)->first(['id','name','price','images']);
            $cartItems[]=[
                'product_id' => $product_id ,
                'name' => $product->name ,
                'image' => $product->images[0],
                'unit_amount' => $product->price ,
                'total_amount' => $product->price ,
                'quantity' => 1
            ] ;
        }
        self::addCartItemsToCookie($cartItems);
        return count($cartItems);
    }
    // add item to cart with Qty
    static public function addItemToCartWithQty($product_id,$Qty){
        $cartItems = self::getCartItemsFromCookie();
        $existing_item = null ;
        foreach($cartItems as $key => $item){
            if($item['product_id'] == $product_id){
                $existing_item = $key ;
                break ;
            }
        }
        if($existing_item !== null){
            $cartItems[$existing_item]['quantity'] += $Qty ;
            $cartItems[$existing_item]['total_amount'] = $cartItems[$existing_item]['unit_amount'] * $cartItems[$existing_item]['quantity'];
        }
        else {
            $product = Product::where('id',$product_id)->first(['id','name','price','images']);
            $cartItems[]=[
                'product_id' => $product_id ,
                'name' => $product->name ,
                'image' => $product->images[0],
                'unit_amount' => $product->price ,
                'total_amount' => $product->price ,
                'quantity' => $Qty
            ] ;
        }
        self::addCartItemsToCookie($cartItems);
        return count($cartItems);
    }

    static public function getItemFromCartIfExist($product_id){
        $cartItems = self::getCartItemsFromCookie();
        $existing_item = null ;
        foreach($cartItems as $key => $item){
            if($item['product_id'] == $product_id){
                $existing_item = $key ;
                break ;
            }
        }
        if($existing_item !== null){
            return $cartItems[$existing_item]['quantity'];
        }
        return null ;
    }
    // add cart item to cookie
    static public function addCartItemsToCookie($cartItems){
        Cookie::queue('cartItems' , json_encode($cartItems), 60 * 24 * 50 );
    }

    // remove item from cart
    static public function removeCartItem($product_id){
        $cartItems = self::getCartItemsFromCookie();
        foreach($cartItems as $key => $item){
            if($item['product_id'] == $product_id){
                unset($cartItems[$key]);
            }
        }
        self::addCartItemsToCookie($cartItems);
        return $cartItems ;
    }

    // get all cart items from cookie
    static public function getCartItemsFromCookie(){
        $cartItems = json_decode(cookie::get('cartItems'),true);
        if(!$cartItems){
            $cartItems = [];
        }
        return $cartItems ;
    }

    // clear cart items from cookie
    static public function clearCartItemsFromCookie(){
        Cookie::queue(Cookie::forget('cartItems'));
    }

    // increment item quantity
    static public function incrementQuantityToCartItem($product_id){
        $cartItems = self::getCartItemsFromCookie();
        foreach($cartItems as $key => $item){
            if($item['product_id'] == $product_id){
                $cartItems[$key]['quantity']++ ;
                $cartItems[$key]['total_amount'] = $cartItems[$key]['unit_amount'] * $cartItems[$key]['quantity'];
            }
        }
        self::addCartItemsToCookie($cartItems);
        return $cartItems ;
    }

    // decrement item quantity
    static public function decrementQuabtityFromCartItem($product_id){
        $cartItems = self::getCartItemsFromCookie();
        foreach($cartItems as $key => $item){
            if($item['product_id'] == $product_id){
                if($cartItems[$key]['quantity'] > 1){
                    $cartItems[$key]['quantity']-- ;
                    $cartItems[$key]['total_amount'] = $cartItems[$key]['unit_amount'] * $cartItems[$key]['quantity'];
                }
            }
        }
        self::addCartItemsToCookie($cartItems);
        return $cartItems ;
    }

    // calculate grand total
    static public function calculateGrandTotal($items){
        return array_sum(array_column($items , 'total_amount'));
    }
}



?>
