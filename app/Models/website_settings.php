<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class website_settings extends Model
{
    use HasFactory;
    protected $fillable = [
        'main_logo',
        'products_per_page',
        'home_products_count',
        'home_ads_count',
        'contact_email',
        'contact_phone',
        'footer_text',
    ];
}
