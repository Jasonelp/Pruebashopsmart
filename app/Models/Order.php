<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'status',
        'shipping_address',
        'notes',
        'payment_method',
        'cancellation_reason'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pendiente');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'entregado');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pendiente' => 'bg-yellow-100 text-yellow-800',
            'confirmado' => 'bg-blue-100 text-blue-800',
            'enviado' => 'bg-purple-100 text-purple-800',
            'entregado' => 'bg-green-100 text-green-800',
            'cancelado' => 'bg-red-100 text-red-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pendiente' => 'Pendiente',
            'confirmado' => 'Confirmado',
            'enviado' => 'Enviado',
            'entregado' => 'Entregado',
            'cancelado' => 'Cancelado',
        ];

        return $labels[$this->status] ?? 'Desconocido';
    }

    public function canBeCancelled()
    {
        return $this->status === 'pendiente';
    }
}