<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payments extends Model
{
    use HasFactory;
    protected $fillable = ['payment_method_id','order_id','amount','data'];

    protected $casts = [
        // 'data' => 'string',
        'amount' => 'decimal:2',
    ];

    public function paymentMethod()
    {
        return $this->belongsTo(payment_methods::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
