<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Inertia\Inertia;
use App\Helpers\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ChekcoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carts = Cart::getCartItems($image = false);
        $total = Cart::getTotal();
        if (Auth::check()) {
            $user = User::find(Auth::user()->id)->with('addresses')->first();
            return Inertia::render('User/Checkout', [
                'carts' => $carts,
                'total' => $total,
                'user' => $user
            ]);
        }

        return Inertia::render('User/Checkout', [
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
        // Create order
        $order = Order::create([
            'user_id' => Auth::user()->id,
            'user_address_id' => $request->user_address_id,
            'total' => Cart::getTotal(),
            'status' => 'pending',
            'session_id' => session()->getId()
        ]);

        // Create order items
        $carts = Cart::getCartItems();
        foreach ($carts as $cart) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cart['id'],
                'quantity' => $cart['quantity'],
                'unit_price' => $cart['price']
            ]);
        }

        // Clear cart
        Cart::clearCartItems(Auth::user()->id);

        return redirect()->route('user.checkout.order');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
