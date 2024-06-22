<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // if (!Auth::check()) {
        //     $cart = session()->get('cart', []);
        //     $cartItems = Product::whereIn('id', array_keys($cart))->get();
        // } else {
        //     $cartItems = CartItem::where('user_id', Auth::user()->id)->get();
        // }

        $products = Product::where('published', 1)->with('category', 'brand', 'images')->latest()->take(8)->get();
        $brands = Brand::take(6)->get();
        return Inertia::render('User/Home', [
            'products' => $products,
            'brands' => $brands,
        ]);
    }
}
