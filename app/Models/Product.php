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
        'min_stock_threshold'
    ];

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
}
