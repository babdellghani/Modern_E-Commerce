<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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

    public function firstImage()
    {
        return $this->hasOne(ProductImage::class)->orderBy('id', 'asc');
    }
}
