<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::check()) {
            $carts = session()->get('cart', []);
            $products = Product::find(array_keys($carts));
            return Inertia::render('User/Cart', [
                'productsCart' => $products,
                'carts' => $carts
            ]);
        } else {
            $user_id = Auth::user()->id;
            $carts = CartItem::where('user_id', $user_id)->with('product:id,name,price', 'product.images')->get();
            return Inertia::render('User/Cart', [
                'carts' => $carts
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1' ,
        ]);
        if (!Auth::check()) {
            $product_id = $request->product_id;
            $quantity = $request->quantity;

            $carts = session()->get('cart', []);

            // If the product already exists in the cart, update the quantity
            if (isset($carts[$product_id])) {
                $carts[$product_id] += $quantity;
            } else {
                // Otherwise, add the product to the cart
                $carts[$product_id] = $quantity;
            }

            session()->put('cart', $carts);

            return redirect('/store.cart')->with('success', 'Item added to cart successfully');
        } else {
            $user_id = Auth::user()->id;
            $product_id = $request->product_id;
            $quantity = $request->quantity;

            // Check if the product already exists in the cart for the user
            $existingCartItem = CartItem::where('user_id', $user_id)->where('product_id', $product_id)->first();

            if ($existingCartItem) {
                // If it exists, update the quantity
                $existingCartItem->quantity += $quantity;
                $existingCartItem->save();
                return redirect()->back()->with('success', 'Item updated in cart successfully');
            } else {
                // If it doesn't exist, create a new cart item
                $cart = new CartItem();
                $cart->user_id = $user_id;
                $cart->product_id = $product_id;
                $cart->quantity = $quantity;
                $cart->save();
                return redirect()->back()->with('success', 'Item added to cart successfully');
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1' . Product::find($request->product_id)->quantity,
        ]);

        if (!Auth::check()) {
            $product_id = $request->product_id;
            $quantity = $request->quantity;
            $carts = session()->get('cart', []);
            $carts[$product_id] = $quantity;
            session()->put('cart', $carts);
            return redirect()->back()->with('success', 'Item updated in cart successfully');
        } else {
            $user_id = Auth::user()->id;
            $product_id = $request->product_id;
            $quantity = $request->quantity;

            $cart = CartItem::where('user_id', $user_id)->where('product_id', $product_id)->first();
            $cart->quantity = $quantity;
            $cart->save();

            return redirect()->back()->with('success', 'Item updated in cart successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!Auth::check()) {
            $carts = session()->get('cart', []);
            unset($carts[$id]);
            session()->put('cart', $carts);
            return redirect()->back()->with('success', 'Item removed from cart successfully');
        } else {
            $user_id = Auth::user()->id;
            $cart = CartItem::where('user_id', $user_id)->where('product_id', $id)->first();
            $cart->delete();
            return redirect()->back()->with('success', 'Item removed from cart successfully');
        }
    }
}
