<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Contracts\Auth\MustVerifyEmail;
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
        // 必要に応じて他のカラムを追加
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // ユーザーが出品した商品のリレーション（hasMany）
    public function exhibitedProducts()
    {
        return $this->hasMany(Product::class);
    }

    // ユーザーが「いいね」した商品のリレーション（多対多）
    public function likedProducts()
    {
        return $this->belongsToMany(Product::class, 'likes', 'user_id', 'product_id');
    }

    // ユーザーが購入した商品のリレーション（buyer_id を利用）
    public function purchasedProducts()
    {
        return $this->hasMany(Product::class, 'buyer_id');
    }
}
