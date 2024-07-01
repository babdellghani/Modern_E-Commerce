<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;
use App\Helpers\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChekcoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carts = Cart::getCartItems($image = true);
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
        //
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
