<?php

namespace App\Helpers;

use App\Models\Product;
use App\Models\CartItem;
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

    /**
     * Set the cart items in the session.
     * 
     * @return void
     */
    public static function setCartItemsSession(int $product_id, int $quantity)
    {
        $carts = self::getSessionCartItems();

        // If the product already exists in the cart, update the quantity
        if (isset($carts[$product_id])) {
            $carts[$product_id] += $quantity;
        } else {
            // Otherwise, add the product to the cart
            $carts[$product_id] = $quantity;
        }

        session()->put('cart', $carts);
    }

    /**
     * Update the cart item in the session.
     * 
     * @return void
     */
    public static function updateCartItemsSession(int $product_id, int $quantity)
    {
        $carts = self::getSessionCartItems();
        $carts[$product_id] = $quantity;
        session()->put('cart', $carts);
    }

    /**
     * Update the cart item in the user.
     * 
     * @return void
     */
    public static function updateCartItemsUser(int $user_id, int $product_id, int $quantity)
    {
        $cart = CartItem::where('user_id', $user_id)->where('product_id', $product_id)->firstOrFail();
        $cart->quantity = $quantity;
        $cart->save();
    }

    /**
     * Set the cart items User.
     * 
     * @return void
     */
    public static function setCartItemsUser(int $user_id, int $product_id, int $quantity)
    {
        $existingCartItem = CartItem::where('user_id', $user_id)->where('product_id', $product_id)->first();

        if ($existingCartItem) {
            // If it exists, update the quantity
            $existingCartItem->quantity += $quantity;
            $existingCartItem->save();
        } else {
            // If it doesn't exist, create a new cart item
            $cart = new CartItem();
            $cart->user_id = $user_id;
            $cart->product_id = $product_id;
            $cart->quantity = $quantity;
            $cart->save();
        }
    }

    /**
     * Remove the cart item from the session.
     * 
     * @return void
     */
    public static function removeCartItemsSession(int $product_id)
    {
        $carts = self::getSessionCartItems();
        unset($carts[$product_id]);
        session()->put('cart', $carts);
    }

    /**
     * Remove the cart item from the user.
     * 
     * @return void
     */
    public static function removeCartItemsUser(int $user_id, int $product_id)
    {
        $cart = CartItem::where('user_id', $user_id)->where('product_id', $product_id)->firstOrFail();
        $cart->delete();
    }

    /**
     * Clear the cart items for User.
     * 
     * @return void
     */
    public static function clearCartItems(int $user_id)
    {
        CartItem::where('user_id', $user_id)->delete();
    }
}
