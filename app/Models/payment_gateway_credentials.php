<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payment_gateway_credentials extends Model
{
    use HasFactory;
    protected $fillable = ['payment_method_id', 'key', 'value'];

    public function paymentMethod()
    {
        return $this->belongsTo(payment_methods::class);
    }
}
