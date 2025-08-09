<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'quantity',
        'is_active',
        'created_by'
    ];

    // Product Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function ratings()
    {
        return $this->hasMany(ProductRating::class);
    }

    public function sales()
    {
        return $this->hasMany(ProductSale::class);
    }

    public function categories()
    {
        return $this->hasMany(ProductCategory::class);
    }

    // Other Relationships
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    // Single Attributes
    public function userWishlist()
    {
        return $this->hasOne(Wishlist::class)->where('user_id', Auth::id());
    }

    public function userCart()
    {
        return $this->hasOne(Cart::class)->where('user_id', Auth::id());
    }
}
