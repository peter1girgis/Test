<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Mail\OrderPlaced;
use App\Models\Addresses;
use App\Models\Order;
use App\Models\payment_methods;
use App\Models\Product;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Title;
use Livewire\Component;
use Stripe\Checkout\Session;
use Stripe\Stripe;

#[Title('CHECKOUT - PeterStore')]
class CheckoutPage extends Component
{

    public $last_name ;
    public $phone ;
    public $street_address ;
    public $city ;
    public $state ;
    public $payment_method ;
    // public $payment_id ;
    public $zip_code ;
    public $first_name ;
    public function mount (){
        $cartItems = CartManagement::getCartItemsFromCookie();
        if(count($cartItems) == 0 ){
            return redirect('/products');
        }
    }
    public function placeOrder(){
        $this->validate([
            'last_name' => 'required',
            'phone' => 'required',
            'street_address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'payment_method' => 'required',
            'zip_code' => 'required',
            'first_name' => 'required|min:4|max:255',
        ]);
        $cartItems = CartManagement::getCartItemsFromCookie();
        $lineItems = [];
        foreach($cartItems as $item){
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => $item['unit_amount'] * 100 ,
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                ],
                'quantity' => $item['quantity'],
            ];
        }
        $order = new Order ;
        $order->user_id = auth()->user()->id ;
        $order->grand_total = CartManagement::calculateGrandTotal($cartItems);
        $order->payment_method = $this->payment_method ;
        $order->payment_status = 'pending';
        $order->status = 'new';
        $order->currency = 'usd' ;
        $order->shipping_amount = 0 ;
        $order->shipping_method = 'none';
        $order->notes = 'this order placed by '. auth()->user()->name ;
        $address = new Addresses ;
        $address->first_name = $this->first_name ;
        $address->last_name = $this->last_name ;
        $address->phone= $this->phone ;
        $address->street_address = $this->street_address ;
        $address->city = $this->city ;
        $address->state = $this->state ;
        $address->zip_code = $this->zip_code ;
        $order->save();
        $redirect_url = '';
        if($this->payment_method == 'stripe'){
            Stripe::setApiKey(env('STIPE_SECRET'));
            $sessionCheckout = Session::create([
                'payment_method_types' => ['card'],
                'customer_email' => auth()->user()->email ,
                'line_items' => $lineItems ,
                'mode' => 'payment',
                'success_url' => route('success').'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('cancel'),
            ]);
            $redirect_url = $sessionCheckout->url ;
        }if ($this->payment_method == 'cod') {
            $redirect_url = route('success');
        }else{
            $payment_id = payment_methods::where('name',$this->payment_method)->first();
            $redirect_url = route('payment',['payment_id' => $payment_id ,'Order' => $order]);
        }
        $order->save();
        $address->order_id = $order->id ;
        $address->save() ;
        $order->items()->createMany($cartItems);
        CartManagement::clearCartItemsFromCookie();
        $this->reset();
        // Mail::to(request()->user()->email)->send(new OrderPlaced($order));
        return redirect($redirect_url);
    }
    public function render()
    {
        $payment_method = payment_methods::where('type','manual')->get();
        $cartItems = CartManagement::getCartItemsFromCookie();
        $grandTotal = CartManagement::calculateGrandTotal($cartItems);
        return view('livewire.checkout-page',[
            'cartItems' => $cartItems,
            'grandTotal' => $grandTotal ,
            'payment_methods' => $payment_method,
        ]);
    }
}
