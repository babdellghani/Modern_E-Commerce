<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where('published', 1)->with('category', 'brand', 'images')->orderBy('id', 'desc')->take(8)->get();
        $brands = Brand::take(6)->get();
        return Inertia::render('User/Home', [
            'products' => $products
        ]);
    }
}
