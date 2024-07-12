<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Brand;

class ProductViewController extends Controller
{
    public function index()
    {
        $products = Product::where('published', 1)->with('category', 'brand', 'images');
        $products = $products->Filters()->SortBy()->paginate(12);


        $barnds = Brand::whereHas('products', function ($query) {
            $query->where('published', 1);
        })->get();
        $categories = $categories = Category::whereHas('products', function ($query) {
            $query->where('published', 1);
        })->get();
        return Inertia::render('User/ProductList', [
            'products' => $products,
            'brands' => $barnds,
            'categories' => $categories
        ]);
    }

    public function show(Product $product)
    {
        return Inertia::render('User/ProductView', [
            'product' => $product
        ]);
    }
}
