<?php

namespace App\Livewire\Partials;

use App\Helpers\CartManagement;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class Navbar extends Component
{
    public $total_count = 0 ;

    public $locale;
    public function mount (){
        $this->locale = App::getLocale();
        $this->total_count = count(CartManagement::getCartItemsFromCookie());
    }
    #[On('update-cart-count')]
    public function updateCartCount($total_count){
        $this->total_count = $total_count ;
    }




    public function switchLanguage()
    {
        $newLocale = $this->locale === 'ar' ? 'en' : 'ar';
        Session::put('locale', $newLocale);
        $this->locale = $newLocale;

        // عمل refresh للصفحة عشان كل الترجمة تتحدث
        return redirect(request()->header('Referer'));
    }
    public function render()
    {
        return view('livewire.partials.navbar');
    }
}
