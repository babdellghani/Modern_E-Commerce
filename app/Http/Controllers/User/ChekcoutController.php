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
        $request->validate([
            'user_address_id' => 'required',
            'integer',
            'exists:user_addresses,id'
        ]);

        if (!Auth::check() || Cart::getTotal() == 0) {
            return redirect()->back()->with('error', 'Cart is empty');
        }

        // Stripe
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $stripe->checkout->sessions->create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Ecommerce Pro Subscription',
                        ],
                        'unit_amount' => Cart::getTotal(),
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'subscription',
            'success_url' => route('order.show', ':CHECKOUT_SESSION_ID'),
            'cancel_url' => route('checkout.index'),
        ]);

        // Create order
        $order = Order::create([
            'user_address_id' => $request->user_address_id,
            'total_price' => Cart::getTotal(),
            'status' => 'pending',
            'session_id' => session()->getId(),
            'created_by' => Auth::user()->id
        ]);

        // Create order items
        $carts = Cart::getCartItems();
        foreach ($carts as $cart) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cart['product_id'],
                'quantity' => $cart['quantity'],
                'unit_price' => $cart['product']['price'],
            ]);
        }

        // Clear cart
        Cart::clearCartItems(Auth::user()->id);

        return redirect()->route('order.show', $order->id)->with('success', 'Order Submitted');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $orders = Order::where('id', $id)->with('address', 'items.product')->firstOrFail();
        return Inertia::render('User/Order', [
            'orders' => $orders
        ]);
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
