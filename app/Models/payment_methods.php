<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payment_methods extends Model
{
    use HasFactory;
    protected $fillable = ['name','type','description'];


    public function requirements()
        {
            return $this->belongsToMany(requirements::class, 'payment_method_requirements', 'payment_method_id', 'requirement_id')
        ->withPivot(['is_required', 'width', 'description'])
        ->withTimestamps();
        }
    public function getewayCredentials()
        {
            return $this->hasMany(payment_gateway_credentials::class, 'payment_method_id');
        }
        public  function Payments()  {
            return $this->hasOne(payments::class, 'payment_method_id');
        }


}
