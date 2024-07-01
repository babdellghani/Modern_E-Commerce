<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'address_1', 'address_2', 'city', 'state', 'postal_code', 'country_code', 'phone', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
