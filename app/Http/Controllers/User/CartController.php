<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;
use App\Helpers\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Rules\ValidProductQuantity;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carts = Cart::getCartItems($image = true);
        $total = Cart::getTotal();
        return Inertia::render('User/Cart', [
            'carts' => $carts,
            'total' => $total
        ]);
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
            'quantity' => 'required|integer|min:1|max:' . Product::find($request->product_id)->quantity,
        ]);

        if (!Auth::check()) {
            $product_id = $request->product_id;
            $quantity = $request->quantity;
            Cart::setCartItemsSession($product_id, $quantity);
            return redirect()->back()->with('success', 'Item added to cart successfully');
        } else {
            $user_id = Auth::user()->id;
            $product_id = $request->product_id;
            $quantity = $request->quantity;
            Cart::setCartItemsUser($user_id, $product_id, $quantity);
            return redirect()->back()->with('success', 'Item added to cart successfully');
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
            'quantity' => ['required', 'integer', 'min:1', new ValidProductQuantity($request->product_id)],
        ]);

        if (!Auth::check()) {
            $product_id = $request->product_id;
            $quantity = $request->quantity;
            Cart::updateCartItemsSession($product_id, $quantity);
            return redirect()->back()->with('success', 'Item updated in cart successfully');
        } else {
            $user_id = Auth::user()->id;
            $product_id = $request->product_id;
            $quantity = $request->quantity;

            Cart::updateCartItemsUser($user_id, $product_id, $quantity);

            return redirect()->back()->with('success', 'Item updated in cart successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!Auth::check()) {
            Cart::removeCartItemsSession($id);
            return redirect()->back()->with('success', 'Item removed from cart successfully');
        } else {
            $user_id = Auth::user()->id;
            Cart::removeCartItemsUser($user_id, $id);
            return redirect()->back()->with('success', 'Item removed from cart successfully');
        }
    }
}
