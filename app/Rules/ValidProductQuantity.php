<?php

namespace App\Rules;

use Closure;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidProductQuantity implements ValidationRule
{
    public $productId;
    public function __construct($productId)
    {
        $this->productId = $productId;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user_id = Auth::check() ? Auth::user()->id : null;
        $product = Product::findOrFail($this->productId);
        if ($value > $product->quantity) {
            $cart = CartItem::where('user_id', $user_id)
                ->where('product_id', $product->id)
                ->firstOrFail();
            $cart->quantity = $product->quantity;
            $cart->save();
            $fail('The quantity exceeds the available stock.');
        }
    }
}
