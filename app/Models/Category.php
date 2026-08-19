<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'name_bn', 'slug', 'image', 'description', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getTranslatedNameAttribute()
    {
        $locale = app()->getLocale();
        if ($locale === 'bn' && !empty($this->name_bn)) {
            return $this->name_bn;
        }
        return $this->name;
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function activeProducts()
    {
        return $this->hasMany(Product::class)->where('is_active', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
