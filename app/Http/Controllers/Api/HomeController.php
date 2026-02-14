<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Categories;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $brands = Brand::where('is_active', 1)->get();
        $categories = Categories::where('is_active', 1)->get();

        return response()->json([
            'status' => 'success',
            'brands' => $brands,
            'categories' => $categories,
        ]);
    }
}
