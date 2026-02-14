<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Categories;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->where('is_active', 1);

        // ✅ فلترة بالـ categories
        if ($request->filled('categories')) {
            $categories = explode(',', $request->categories);
            $query->whereIn('category_id', $categories);
        }

        // ✅ فلترة بالـ brands
        if ($request->filled('brands')) {
            $brands = explode(',', $request->brands);
            $query->whereIn('brand_id', $brands);
        }

        // ✅ featured
        if ($request->boolean('is_featured')) {
            $query->where('is_featured', 1);
        }

        // ✅ on_sale
        if ($request->boolean('on_sale')) {
            $query->where('on_sale', 1);
        }

        // ✅ price_range
        if ($request->filled('price_range')) {
            $query->whereBetween('price', [0, $request->price_range]);
        }

        // ✅ ترتيب
        if ($request->sort === 'price') {
            $query->orderBy('price');
        } else {
            $query->latest();
        }

        // ✅ Pagination
        $products = $query->paginate(12); // نفس paginate() اللي عندك

        return response()->json([
            'filters' => [
                'brands' => Brand::where('is_active', 1)->get(['id', 'name', 'slug']),
                'categories' => Categories::where('is_active', 1)->get(['id', 'name', 'slug']),
            ],
            'products' => $products,
        ]);
    }
}
