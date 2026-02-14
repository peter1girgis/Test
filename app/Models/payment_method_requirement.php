<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payment_method_requirement extends Model
{
    use HasFactory;
    protected $fillable = ['payment_method_id','requirement_id','is_required','width','description'];
    protected $casts = [
        'is_required' => 'boolean',
    ];
    protected $table = 'payment_method_requirements';
}
