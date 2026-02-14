<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Jantinnerezo\LivewireAlert\Concerns\SweetAlert2;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;


#[Title('CART - PeterStore')]
class CartPage extends Component
{
    use SweetAlert2;
    public $cartItems = [];
    public $grandTotal ;
    public function getComponentProperty(){
        return $this ;
    }
    public function mount(){
        $this->cartItems = CartManagement::getCartItemsFromCookie();
        $this->grandTotal = CartManagement::calculateGrandTotal($this->cartItems);
    }
    public function removeItem($product_id){
        $this->cartItems = CartManagement::removeCartItem($product_id);
        $this->grandTotal = CartManagement::calculateGrandTotal($this->cartItems);
        $this->dispatch('update-cart-count', total_count: count($this->cartItems))->to(Navbar::class);
        $this->alert([
            'type' => 'success',
            'title' => 'Item removed from cart successfully!',
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true,
            'iconColor' => '#74fc86',
            'background' => '#ebfaed',
            'icon' => 'success',
            'showConfirmButton' => false,
            'showCloseButton' => false,
            // 'showClass' => [
            //     'popup' => 'animate__animated animate__fadeInDown',
            // ],
            // 'hideClass' => [
            //     'popup' => 'animate__animated animate__fadeOutUp',
            // ],
        ]);
    }
    public function increaceQty($product_id){
        $this->cartItems = CartManagement::incrementQuantityToCartItem($product_id);
        $this->grandTotal = CartManagement::calculateGrandTotal($this->cartItems);
    }
    public function decreaceQty($product_id){
        $this->cartItems = CartManagement::decrementQuabtityFromCartItem($product_id);
        $this->grandTotal = CartManagement::calculateGrandTotal($this->cartItems);
    }

    public function render()
    {
        return view('livewire.cart-page');
    }
}
