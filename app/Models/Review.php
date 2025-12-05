<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * Relación con Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relación con User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para reviews aprobadas
     */
    public function scopeApproved($query)
    {
        return $query->where('rating', '>=', 1);
    }
}
