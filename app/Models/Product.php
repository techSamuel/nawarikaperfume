<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'name_bn', 'slug', 'description', 'description_bn', 'price',
        'sale_price', 'image', 'gallery', 'videos', 'stock', 'is_active', 'is_featured'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'gallery' => 'array',
        'videos' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
                $count = static::where('slug', 'like', $product->slug . '%')->count();
                if ($count > 0) {
                    $product->slug .= '-' . ($count + 1);
                }
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function getDisplayPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    public function getDiscountPercentAttribute()
    {
        if ($this->sale_price && $this->price > 0) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }

    public function isOnSale()
    {
        return $this->sale_price && $this->sale_price < $this->price;
    }

    public function getTranslatedNameAttribute()
    {
        $locale = app()->getLocale();
        if ($locale === 'bn' && !empty($this->name_bn)) {
            return $this->name_bn;
        }
        return $this->name;
    }

    public function getTranslatedDescriptionAttribute()
    {
        $locale = app()->getLocale();
        if ($locale === 'bn' && !empty($this->description_bn)) {
            return $this->description_bn;
        }
        return $this->description;
    }

    public function isInStock()
    {
        return $this->stock > 0;
    }
}
