<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Categories;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
#[Title('HOME - PeterStore')]
class HomePage extends Component
{
    use WithPagination ;
    public function render()
    {
        
        return view('livewire.home-page',[

            'brands' => Brand::where('is_active',1)->get(),
            'categories' => Categories::where('is_active',1)->get(),
        ]);
    }
}
