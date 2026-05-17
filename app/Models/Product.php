<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'user_id', 
        'category_id', 
        'name', 
        'sku', 
        'price', 
        'stock', 
        'image',
        'min_stock_threshold',
        'expired_at',
        'discount',
        'tax'
    ];

    protected function casts(): array
    {
        return [
            'expired_at' => 'date',
            'discount' => 'decimal:2',
            'tax' => 'decimal:2',
        ];
    }

    public function getNetPriceAttribute()
    {
        $afterDiscount = $this->price - $this->discount;
        $taxAmount = $afterDiscount * ($this->tax / 100);
        return max(0, $afterDiscount + $taxAmount);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function isLowStock()
    {
        return $this->stock <= $this->min_stock_threshold;
    }

    public function isExpired()
    {
        return $this->expired_at && $this->expired_at->isPast();
    }

    public function isNearExpiry($days = 30)
    {
        if (!$this->expired_at) return false;
        return !$this->isExpired() && $this->expired_at->diffInDays(now()) <= $days;
    }

    public function expiryStatus()
    {
        if (!$this->expired_at) return null;
        if ($this->isExpired()) return 'expired';
        if ($this->isNearExpiry(7)) return 'critical';
        if ($this->isNearExpiry(30)) return 'warning';
        return 'safe';
    }
}
