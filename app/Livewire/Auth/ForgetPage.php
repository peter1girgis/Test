<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Jantinnerezo\LivewireAlert\Concerns\SweetAlert2;
use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('FORGOT PASSWORD')]
class ForgetPage extends Component
{
    use SweetAlert2 ;
    public $email ;
    public function getComponentProperty(){
        return $this ;
    }

    public function save (){
        $this->validate([
            'email' => 'required|email|exists:users,email|max:255'
        ]);
        $status = Password::sendResetLink(
            ['email' => $this->email]
        );
        if($status === Password::RESET_LINK_SENT){
            // session()->flash('success', 'A reset link has been sent to your email address.');
            $this->alert([
                'type' => 'success',
                'title' => 'A reset link has been sent to your email address.',
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
            $this->email = ''; // Clear the email field after sending the link
        }
    }
    public function render()
    {
        return view('livewire.auth.forget-page');
    }
}
