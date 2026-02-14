<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class requirements extends Model
{
    use HasFactory;
    protected $fillable = ['key','label','type'];
    protected $table = 'requirements';


    public function paymentMethods()
        {
            return $this->belongsToMany(payment_methods::class, 'payment_method_requirements', 'requirement_id', 'payment_method_id')
        ->withPivot(['is_required', 'width', 'description'])
        ->withTimestamps();
        }

}
