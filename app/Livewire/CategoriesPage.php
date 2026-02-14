<?php

namespace App\Livewire;

use App\Models\Categories;

use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('CATEGORIES - PeterStore')]
class CategoriesPage extends Component
{
    public function render()
    {
        $categories = Categories::all();
        return view('livewire.categories-page',[
            'categories' => $categories,
        ]);
    }
}
