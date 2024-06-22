<?php

namespace App\Listeners;

use App\Models\CartItem;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Contracts\Queue\ShouldQueue;

class MigrateSessionCart
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * 
     * @param  Authenticated  $event
     * @return void
     */
    public function handle(Authenticated $event): void
    {
        $user = $event->user;

        $sessionCart = session()->get('cart', []);
        
        if (!empty($sessionCart)) {
            foreach ($sessionCart as $product_id => $quantity) {
                $existingCartItem = CartItem::where('user_id', $user->id)
                    ->where('product_id', $product_id)
                    ->first();

                if ($existingCartItem) {
                    if (($existingCartItem->quantity += $quantity) > $existingCartItem->product->quantity) {
                        $existingCartItem->quantity = $existingCartItem->product->quantity;
                        $existingCartItem->save();
                    } elseif ($quantity <= $existingCartItem->product->quantity) {
                        // If it exists, update the quantity
                        $existingCartItem->quantity += $quantity;
                        $existingCartItem->save();
                    }
                } else {
                    // If it doesn't exist, create a new cart item
                    $cart = new CartItem();
                    $cart->user_id = $user->id;
                    $cart->product_id = $product_id;
                    $cart->quantity = $quantity;
                    $cart->save();
                }
            }
            // Clear session cart after migration
            session()->forget('cart');
        }
    }
}
