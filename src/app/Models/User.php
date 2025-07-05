<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Product;
use App\Models\Comment;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /* ────── 一括代入を許可する属性 ────── */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url',
    ];

    /* ────── 非表示にする属性 ────── */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /* ────── キャスト定義 ────── */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /* =================================================
       リレーション
    ================================================= */

    /** 出品した商品（1 : 多） */
    public function exhibitedProducts()
    {
        return $this->hasMany(Product::class);
    }

    /** いいねした商品（多 : 多、中間テーブル likes） */
    public function likedProducts()
    {
        return $this->belongsToMany(Product::class, 'likes', 'user_id', 'product_id');
    }

    /** 購入した商品（1 : 多） */
    public function purchasedProducts()
    {
        return $this->hasMany(Product::class, 'buyer_id');
    }

    /** コメント（1 : 多） */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /** 送付先住所（1 : 1）※ここだけを使用 */
    public function userAddress()
    {
        return $this->hasOne(\App\Models\Address::class);
    }
}
