<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Brand;
use App\Models\Categories;
use App\Models\Product;
use Jantinnerezo\LivewireAlert\Concerns\SweetAlert2;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
// use Jantinnerezo\LivewireAlert\LivewireAlert;
#[Title('PRODUCTS - PeterStore')]
class ProductsPage extends Component
{
    use SweetAlert2;


    use WithPagination ;


    #[Url]
    public $selected_categories = [];
    #[Url]
    public $selected_brands = [];
    #[Url]
    public $is_featured ;
    #[Url]
    public $on_sale ;
    #[Url]
    public $price_range = 300000 ;
    #[Url]
    public $sort = "latest";
    #[Url]
    public $search = '';


    public array $alerts = [];
    // public ?Component $component ;
    public function getComponentProperty()
    {
        return $this;
    }

    // add product to cart method

    public function addToCart($product_id){
        $total_count = CartManagement::addItemToCart($product_id);
        $this->dispatch('update-cart-count' , total_count: $total_count)->to(Navbar::class);
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
        $productQuery = Product::query()->where('is_active',1);
        if(!empty($this->selected_categories)){
            $productQuery->whereIn('category_id',$this->selected_categories);
        }
        if(!empty($this->selected_brands)){
            $productQuery->whereIn('brand_id',$this->selected_brands);
        }
        if($this->is_featured){
            $productQuery->where('is_featured',1);
        }
        if($this->on_sale){
            $productQuery->where('on_sale',1);
        }

        if($this->price_range){
            $productQuery->whereBetween('price',[0,$this->price_range]);
        }
        if($this->sort == 'latest'){
            $productQuery->latest();
        }
        if($this->sort == "price"){
            $productQuery->orderBy('price');
        }
        if (!empty($this->search)) {
            $productQuery->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.products-page',[
            'products' => $productQuery->paginate(),
            'brands' => Brand::where('is_active',1)->get(['id','name','slug']),
            'categories' => Categories::where('is_active',1)->get(['id','name','slug']),
        ]);
    }
}
