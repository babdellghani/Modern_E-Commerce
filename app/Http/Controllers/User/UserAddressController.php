<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserAddressRequest;
use App\Models\UserAddress;

class UserAddressController extends Controller
{
    public function store(UserAddressRequest $request)
    {
        $address = $request->validated();
        UserAddress::create($address);
        return redirect()->back()->with('success', 'Address added successfully');
    }

    public function destroy($id)
    {
        $address = UserAddress::findorFail($id);
        $address->delete();
        return redirect()->back()->with('success', 'Address deleted successfully');
    }
}
