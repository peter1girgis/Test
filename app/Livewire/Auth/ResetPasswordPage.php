<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\Concerns\SweetAlert2;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
#[Title('RESET PASSWORD')]
class ResetPasswordPage extends Component
{
    use SweetAlert2 ;
    #[Url]
    public $email;
    public $token; // Assuming you will pass the token in the URL
    public $password;
    public $password_confirmation;
    public function getComponentProperty(){
        return $this ;
    }
    public function mount($token){
        $this->token = $token;
        // You might want to validate the token here or use it for password reset logic
    }
    public function save (){
        $this->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email|max:255',
            'password' => 'required|min:6|confirmed',
        ]);

        // Logic to reset the password goes here, e.g., using Password::reset() method
        // For example:
        // $status = Password::reset(
        //     ['email' => $this->email, 'password' => $this->password, 'password_confirmation' => $this->password_confirmation, 'token' => $this->token],
        //     function ($user) {
        //         $user->password = bcrypt($this->password);
        //         $user->save();
        //     }
        // );
        $status = Password::reset([
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
            'token' => $this->token
        ],
        function(User $user,string $password){
            $password = $this->password;
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));
            $user->save();
            event(new PasswordReset($user));
        }
    );
    return $status === Password::PASSWORD_RESET? redirect('/login'):$this->alert([
            'type' => 'error',
            'title' => 'something went wrong',
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true,
            'iconColor' => '#74fc86',
            'background' => '#ebfaed',
            'icon' => 'success',
            'showConfirmButton' => false,
            'showCloseButton' => false,
        ]);



        // Assuming the password reset is successful

    }
    public function render()
    {
        return view('livewire.auth.reset-password-page');
    }
}
