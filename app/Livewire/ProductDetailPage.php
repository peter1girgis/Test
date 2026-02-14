<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Product;
use Jantinnerezo\LivewireAlert\Concerns\SweetAlert2;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('PRODUCTDETAILS - PeterStore')]
class ProductDetailPage extends Component
{
    use SweetAlert2;
    public $product ;
    public $quantity = 1 ;
    public function getComponentProperty(){
        return $this ;
    }

    public function mount($slug){
        $this->product = Product::where('slug', $slug)->firstOrFail();
        $this->update_Qty();

    }
    #[On('update')]
    public function update_Qty(){
        $Item = CartManagement::getItemFromCartIfExist($this->product->id);
        if($Item !== null){
            $this->quantity = $Item;
        }
    }
    public function increaseQty(){
        $this->quantity++ ;
    }
    public function decreaseQty(){
        if($this->quantity > 1){
            $this->quantity-- ;
        }
    }
    public function addToCart($product_id){
        $total_count = CartManagement::addItemToCartWithQty($product_id,$this->quantity);
        $this->dispatch('update-cart-count' , total_count: $total_count)->to(Navbar::class);
        $this->dispatch('update');
        $this->alert([
            'type' => 'success',
            'title' => 'Product added to cart successfully!',
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
        // CartManagement::clearCartItemsFromCookie();
    }
    public function render()
    {

        return view('livewire.product-detail-page',[
            'product' => $this->product ,
        ]);
    }
}
