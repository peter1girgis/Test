<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name','slug','description','images','is_active','price','brand_id','category_id','on_sale','is_featured','in_stock'];
    protected $casts = [
        'images' => "array"
    ];
    public function category(){
        return $this->belongsTo(Categories::class);
    }
    public function brand(){
        return $this->belongsTo(Brand::class);
    }
    public function orders(){
        return $this->belongsToMany(Order::class);
    }
    public function orderIrems(){
        return $this->hasMany(OrderItem::class);
    }

}
