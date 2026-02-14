<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('CANCEL - PeterStore')]
class Cancel extends Component
{
    public function render()
    {
        return view('livewire.cancel');
    }
}
