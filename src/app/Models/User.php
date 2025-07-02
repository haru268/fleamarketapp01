<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /* ---------- リレーション ---------- */

    // 出品した商品
    public function exhibitedProducts()
    {
        return $this->hasMany(Product::class);
    }

    // いいねした商品
    public function likedProducts()
    {
        return $this->belongsToMany(Product::class, 'likes', 'user_id', 'product_id');
    }

    // 購入した商品
    public function purchasedProducts()
    {
        return $this->hasMany(Product::class, 'buyer_id');
    }

    // コメント
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // ★ 送付先住所（1 : 1）
    public function userAddress()
    {
        return $this->hasOne(\App\Models\Address::class);
    }
}
