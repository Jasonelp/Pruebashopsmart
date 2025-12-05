<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'category_id',
        'user_id',
        'specifications',
        'is_active',
        'deactivation_reason'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'specifications' => 'array',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function isInStock()
    {
        return $this->stock > 0;
    }

    public function isAvailable()
    {
        return $this->is_active && $this->stock > 0;
    }

    public function getFormattedPriceAttribute()
    {
        return 'S/ ' . number_format($this->price, 2);
    }

    // Scope for active products only
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}