<?php

namespace App\Helpers;

use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class Cart
{
    /**
     * Get the number of items in the cart.
     * 
     * @return int
     */
    public static function getCount(): int
    {
        if (Auth::check()) {
            $user = Auth::user();
            return CartItem::whereUserId($user->id)->count(); //sum('quantity')
        } else {
            $sessionCartItems = self::getSessionCartItems();

            // Ensure the session cart items are in the expected format
            return count($sessionCartItems);
        }
    }

    /**
     * Get the total items in the cart for the session.
     * 
     * @return array
     */
    public static function getSessionCartItems(): array
    {
        return session()->get('cart', []);
    }

    /**
     * Get the total items in the cart.
     * 
     * @return array
     */
    public static function getCartItems($image = true)
    {
        if (Auth::user()) {
            $user = Auth::user();
            return CartItem::whereUserId($user->id)->with('product:id,name,price')
                ->when($image, function ($query) {
                    return $query->with('product.images');
                })->get()->toArray();
        } else {
            return $image ? self::getSessionCartItemsAndProducts($image = true) : self::getSessionCartItemsAndProducts($image = false);
        }
    }

    /**
     * Set the cart items in the session.
     * 
     * @param array $cartItems
     * @return void
     */
    public static function setSessionCartItems(array $cartItems)
    {
        session()->put('cart', $cartItems);
    }

    /**
     * Get the total price of the cart.
     * 
     * @return float
     */
    public static function getTotal(): float
    {
        return array_reduce(self::getCartItems($image = false), function ($carry, $item) {
            return $carry + $item['quantity'] * $item['product']['price'];
        }, 0);
    }

    /**
     * Get the cart items.
     * 
     * @return array
     */
    public static function getSessionCartItemsAndProducts($image = true): array
    {
        $cart = self::getSessionCartItems();

        // Get KEYS of cart items
        $ids = array_keys($cart);
        if (empty($ids)) {
            return [];
        }

        // Get products
        $products = Product::whereIn('id', $ids)
            ->when($image, function ($query) {
                return $query->with('images');
            })
            ->get();

        // Initialize an empty array for the cart items
        $cartItems = [];

        // Loop through the cart array to build the cart items
        foreach ($cart as $productId => $quantity) {
            $product = $products->firstWhere('id', $productId);
            $cartItems[] = [
                'product' => $product,
                'quantity' => $quantity,
            ];
        }

        return $cartItems;
    }
}
