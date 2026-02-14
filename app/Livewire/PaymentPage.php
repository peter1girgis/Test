<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\payment_methods;
use App\Models\PaymentMethod;
use App\Models\payments;
use Livewire\Component;
use Livewire\WithFileUploads;

class PaymentPage extends Component
{
    use WithFileUploads;

    public $paymentId;
    public $requirements = [];
    public $inputs = [];
    public $order ;

    public function mount($payment_id,$Order){
        $this->paymentId = $payment_id;
        $this->order = Order::findOrFail($Order); ;

        $paymentMethod = payment_methods::with(['requirements' => function ($q) {
            $q->withPivot(['is_required', 'width', 'description']);
        }])->findOrFail($payment_id);

        $this->requirements = $paymentMethod->requirements->map(function ($req) {
            return [
                'id'          => $req->id,
                'key'         => $req->key,
                'label'       => $req->label,
                'type'        => $req->type,
                'is_required' => $req->pivot->is_required,
                'width'       => $req->pivot->width,
                'description' => $req->pivot->description,
            ];
        })->toArray();
    }

    public function submit(){
        $rules = [];

        foreach ($this->requirements as $req) {
            $rule = [];
            if ($req['is_required']) {
                $rule[] = 'required';
            }

            switch ($req['type']) {
                case 'text':
                case 'textarea':
                    $rule[] = 'string';
                    break;
                case 'phone':
                    $rule[] = 'string';
                    $rule[] = 'regex:/^[0-9+\-\(\)\s]+$/';
                    break;
                case 'file':
                    $rule[] = 'file|max:2048';
                    break;
                case 'date':
                    $rule[] = 'date';
                    break;
                case 'number':
                    $rule[] = 'numeric';
                    break;
            }
            $rules['inputs.' . $req['id']] = implode('|', $rule);
        }

        $this->validate($rules);

        $markdown = "";

        foreach ($this->requirements as $req) {
            $input = $this->inputs[$req['id']] ?? null;

            switch ($req['type']) {
                case 'file':
                    if ($input) {
                        $path = $input->store('payments', 'public');
                        $markdown .= "![](" . asset('storage/' . $path) . ")\n";
                    }
                    break;

                case 'phone':
                    $markdown .= $req['label'] . ": " . preg_replace('/\s+/', '', $input) . "\n";
                    break;

                case 'number':
                case 'date':
                case 'text':
                case 'textarea':
                default:
                    $markdown .= $req['label'] . ": " . $input . "\n";
                    break;
            }
        }

        $payment = payments::create([
            'order_id'          => $this->order->id,
            'payment_method_id' => $this->paymentId,
            'amount'            => $this->order->grand_total,
            'data'              => (string) $markdown,
        ]);

        return redirect()->route('success');
}


    public function render()
    {
        return view('livewire.payment-page');
    }
}
