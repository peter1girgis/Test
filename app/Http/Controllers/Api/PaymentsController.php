<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\payment_methods;
use App\Models\payments;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class PaymentsController extends Controller
{
    public function store(Request $request, $payment_id, $orderId)
    {
        try {
            // ✅ جلب الـ Order
            $order = Order::findOrFail($orderId);

            // ✅ جلب الـ Payment Method بالـ requirements
            $paymentMethod = payment_methods::with(['requirements' => function ($q) {
                $q->withPivot(['is_required', 'width', 'description']);
            }])->findOrFail($payment_id);

            // ✅ بناء قواعد التحقق
            $rules = [];
            foreach ($paymentMethod->requirements as $req) {
                $rule = [];
                if ($req->pivot->is_required) {
                    $rule[] = 'required';
                }

                switch ($req->type) {
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

                $rules['inputs.' . $req->id] = implode('|', $rule);
            }

            // ✅ تحقق من البيانات
            $validated = $request->validate($rules);

            // ✅ تجهيز Markdown
            $markdown = "";
            foreach ($paymentMethod->requirements as $req) {
                $input = $request->input("inputs.{$req->id}");

                switch ($req->type) {
                    case 'file':
                        if ($request->hasFile("inputs.{$req->id}")) {
                            $path = $request->file("inputs.{$req->id}")
                                ->store('payments', 'public');
                            $markdown .= "![](" . asset('storage/' . $path) . ")\n";
                        }
                        break;

                    case 'phone':
                        $markdown .= $req->label . ": " . preg_replace('/\s+/', '', $input) . "\n";
                        break;

                    default:
                        $markdown .= $req->label . ": " . $input . "\n";
                        break;
                }
            }

            // ✅ إنشاء payment
            $payment = payments::create([
                'order_id'          => $order->id,
                'payment_method_id' => $paymentMethod->id,
                'amount'            => $order->grand_total,
                'data'              => (string) $markdown,
            ]);

            return response()->json([
                'message' => 'Payment created successfully',
                'payment' => $payment,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
