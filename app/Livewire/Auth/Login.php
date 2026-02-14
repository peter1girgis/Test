<?php

namespace App\Livewire\Auth;

use Jantinnerezo\LivewireAlert\Concerns\SweetAlert2;
use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('Login')]
class Login extends Component
{
    use SweetAlert2 ;
    public $email ;
    public $password ;
    public function getComponentProperty (){
        return $this ;
    }
    public function login(){
        $this->validate([
            'email' => 'required|email|max:255|exists:users,email',
            'password' => 'required|min:6|max:255'
        ]);
        if(!auth()->attempt(['email' => $this->email, 'password' => $this->password])){
            $this->alert(
                [
                    'title' => 'Invalid Credentails',
                    'type' => 'error',
                    'position' => 'bottom-end',
                    'timer' => 3000,
                    'toast' => true,
                    'iconColor' => '#FB542B',
                    'background' => '#ebfaed',
                    'icon' => 'error',
                    'showConfirmButton' => false,
                    'showCloseButton' => false,
                ]
                );
            return;
        }

        return redirect()->intended();

    }
    public function render()
    {
        return view('livewire.auth.login');
    }
}
