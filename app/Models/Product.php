<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['category_id', 'brand_id', 'name', 'slug', 'description', 'quantity', 'price', 'published', 'in_stock', 'created_by', 'updated_by', 'deleted_by'];
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function CartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function scopeFilters(Builder $query)
    {
        return $query->when(request('search'), function ($query) {
            return $query->where('name', 'like', '%' . request('search') . '%');
        })->when(request('category'), function ($query) {
            return $query->where('category_id', request('category'));
        })->when(request('brand'), function ($query) {
            return $query->where('brand_id', request('brand'));
        })->when(request('price'), function ($query) {
            return $query->whereBetween(
                'price',
                [request('price')[0], request('price')[1]]
            );
        });
    }

    public function scopeSortBy(Builder $query)
    {
        return $query->when(request('sort'), function ($query) {
            return $query->orderBy(request('sort'), request('order'));
        });
    }
}
