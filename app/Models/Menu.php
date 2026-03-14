<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Added for the favoritedBy relationship

class Menu extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'image_path',
        'is_new',
        'is_popular',
        'is_promotion',
        'discount_type',
        'discount_value'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(MenuImage::class);
    }

    public function reviews()
    {
        return $this->hasMany(MenuReview::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function getDiscountedPriceAttribute()
    {
        if (!$this->discount_type || !$this->discount_value) {
            return $this->price;
        }

        if ($this->discount_type === 'percent') {
            return max(0, $this->price - ($this->price * ($this->discount_value / 100)));
        }

        if ($this->discount_type === 'fixed') {
            return max(0, $this->price - $this->discount_value);
        }

        return $this->price;
    }
}