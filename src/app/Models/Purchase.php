<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'purchased_at',
    ];

    // 購入したユーザーとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 購入された商品とのリレーション
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
