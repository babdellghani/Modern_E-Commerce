<?php

namespace App\Http\Controllers\User;

use Stripe\Stripe;
use App\Models\User;
use Inertia\Inertia;
use App\Helpers\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Stripe\StripeClient;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate
        $request->validate([
            'user_address_id' => 'required',
            'integer',
            'exists:user_addresses,id'
        ]);
        $total = Cart::getTotal();
        if (!Auth::check() || $total == 0) {
            return redirect()->back()->with('error', 'Cart is empty');
        }

        // Create line items
        $line_items = [];
        $carts = Cart::getCartItems();
        foreach ($carts as $cart) {
            // Create line items
            $line_items[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $cart['product']['name'],
                    ],
                    'unit_amount' => $cart['product']['price'] * 100,
                ],
                'quantity' => $cart['quantity'],
            ];

            // Validate quantity
            if ($cart['product']['quantity'] < $cart['quantity']) {
                return redirect()->route('cart.index')->with('error', 'Product ' . $cart['product']['name'] . ' quantity is bigger than available stock');
            }
        };

        // Stripe
        $stripe = new StripeClient(env('STRIPE_SECRET'));
        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel') . '?session_id={CHECKOUT_SESSION_ID}',
        ]);

        // Store checkout session ID in the session
        session(['checkout_session_id' => $checkout_session->id]);
        session(['user_address_id' => $request->user_address_id]);

        return Inertia::location($checkout_session->url);
    }

    /**
     * Succes the checkout.
     */
    public function success(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $sessionId = $request->get('session_id');
        try {
            $session = Session::retrieve($sessionId);
            if (!$session) {
                throw new NotFoundHttpException;
            }

            if ($session->payment_status !== 'paid') {
                return redirect()->route('cart.index')->with('error', 'Payment not successful');
            }

            // Create order
            $order = Order::create([
                'user_address_id' => session('user_address_id'),
                'total_price' => $session->amount_total / 100,
                'status' => 'paid',
                'session_id' => $sessionId,
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

                // Update product quantity
                $product = Product::find($cart['product_id']);
                $product->quantity -= $cart['quantity'];
                $product->save();
            }

            // Create Payment
            Payment::create([
                'order_id' => $order->id,
                'amount' => $session->amount_total / 100,
                'status' => 'completed',
                'type' => 'stripe',
                'created_by' => Auth::user()->id
            ]);

            // Clear cart
            Cart::clearCartItems(Auth::user()->id);

            // Clear session data
            session()->forget(['checkout_session_id', 'user_address_id']);


            return redirect()->route('user.orders.show', $order->id)->with('success', 'Order Submitted');
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }

    /**
     * Cancel the checkout.
     */
    public function cancel()
    {
        session()->forget(['checkout_session_id', 'user_address_id']);
        return redirect()->route('checkout.index')->with('error', 'Order Cancelled');
    }
}
