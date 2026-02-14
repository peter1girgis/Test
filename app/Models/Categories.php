<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;
    protected $fillable = ['is_active','image','slug','name'];
    public function products(){
        return $this->hasMany(Product::class);
    }
}
