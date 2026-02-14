<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Stripe\Checkout\Session;
use Stripe\Stripe;

#[Title('SUCCESS - PeterStore')]
class Success extends Component
{
    #[Url]
    public $session_id ;
    public function render()
    {
        
        $latest_order = Order::with('address')->where('user_id',auth()->user()->id)->latest()->first();
        
        if($this->session_id){
            Stripe::setApiKey(env('STIPE_SECRET'));
            $session_info = Session::retrieve($this->session_id);
            if($session_info->payment_status != 'paid'){
                $latest_order->payment_status = 'failed';
                $latest_order->save();
            }elseif($session_info->payment_status == 'paid'){
                $latest_order->payment_status = 'paid';
                $latest_order->save();
            }

        }
        
        return view('livewire.success',[
            'latest_order' => $latest_order ,
        ]);
    }
}
