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
        if ($user = Auth::user()) {
            return CartItem::whereUserId($user->id)->count(); //sum('quantity')
        } else {
            return array_reduce(self::getSessionCartItems(), function ($carry, $item) {
                return $carry + $item['quantity'];
            }, 0);
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
    public static function getCartItemsWithoutImage()
    {
        if (Auth::user()) {
            $user = Auth::user();
            return CartItem::whereUserId($user->id)->with('product:id,name,price')->get()->toArray();
        } else {
            return self::getSessionCartItems();
        }
    }

    /**
     * Get the total items in the cart.
     * 
     * @return array
     */
    public static function getCartItemsWithImage()
    {
        if (Auth::user()) {
            $user = Auth::user();
            return CartItem::whereUserId($user->id)->with('product:id,name,price', 'product.images')->get()->toArray();
        } else {
            return self::getSessionCartItems();
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
        return array_reduce(self::getCartItemsWithoutImage(), function ($carry, $item) {
            return $carry + $item['quantity'] * $item['product']['price'];
        }, 0);
    }

    // public static function saveCookieCartItems()
    // {
    //     $user = auth()->user();
    //     $userCartItems = CartItem::where(['user_id' => $user->id])->get()->keyBy('product_id');
    //     $savedCartItems = [];
    //     foreach (self::getCookieCartItems() as $cartItem) {
    //         if (isset($userCartItems[$cartItem['product_id']])) {
    //             $userCartItems[$cartItem['product_id']]->update(['quantity' => $cartItem['quantity']]);
    //             continue;
    //         }
    //         $savedCartItems[] = [
    //             'user_id' => $user->id,
    //             'product_id' => $cartItem['product_id'],
    //             'quantity' => $cartItem['quantity'],
    //         ];
    //     }
    //     if (!empty($savedCartItems)) {
    //         CartItem::insert($savedCartItems);
    //     }
    // }

    // public static function moveCartItemsIntoDb()
    // {
    //     $request = request();
    //     $cartItems = self::getCookieCartItems();
    //     $newCartItems = [];
    //     foreach ($cartItems as $cartItem) {
    //         // Check if the record already exists in the database
    //         $existingCartItem = CartItem::where([
    //             'user_id' => $request->user()->id,
    //             'product_id' => $cartItem['product_id'],
    //         ])->first();

    //         if (!$existingCartItem) {
    //             // Only insert if it doesn't already exist
    //             $newCartItems[] = [
    //                 'user_id' => $request->user()->id,
    //                 'product_id' => $cartItem['product_id'],
    //                 'quantity' => $cartItem['quantity'],
    //             ];
    //         }
    //     }


    //     if (!empty($newCartItems)) {
    //         // Insert the new cart items into the database
    //         CartItem::insert($newCartItems);
    //     }
    // }


    // /**
    //  * Get the cart items.
    //  * 
    //  * @return array
    //  */
    // public static function getProductsAndCartItems()
    // {
    //     $cart = self::getCartItems();

    //     $ids = Arr::pluck($cart, 'product_id');
    //     $products = Product::whereIn('id', $ids)->get();
    //     $cartItems = Arr::map($cart, fn($item) => [
    //         'product' => $products->where('id', $item['product_id'])->first(),
    //         'quantity' => $item['quantity'],
    //     ]);

    //     return $cartItems;
    // }
}
