<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_number', 'name', 'email', 'phone',
        'address', 'city', 'state', 'zip', 'subtotal', 'shipping',
        'total', 'status', 'payment_method', 'notes',
        'ip_address', 'user_agent', 'device_type', 'browser',
        'platform', 'timezone', 'country'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateOrderNumber()
    {
        $prefix = 'ORD-';
        $number = $prefix . strtoupper(uniqid());
        while (static::where('order_number', $number)->exists()) {
            $number = $prefix . strtoupper(uniqid());
        }
        return $number;
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'badge-warning',
            'processing' => 'badge-info',
            'confirm' => 'badge-info',
            'shipped' => 'badge-primary',
            'delivered' => 'badge-success',
            'cancelled' => 'badge-danger',
            default => 'badge-secondary',
        };
    }
}
