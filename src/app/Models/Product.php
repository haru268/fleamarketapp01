<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

  
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'price',
        'category',
        'condition',
        'image',
        'is_recommended',   
        'buyer_id',
         'is_sold',
          'purchased_at',
    ];

    public function user()       
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

   
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    
    public function likedBy($user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'category_id');
    }

   

    /**
     * 推薦商品を取得（is_recommended = 1 && 自分以外）
     *
     * @param \Illuminate\Database\Eloquent\Builder $q
     * @param int                                   $uid
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecommendedFor($q, int $uid)
    {
        return $q->where('is_recommended', true)
                 ->where('user_id', '!=', $uid);
    }
}
