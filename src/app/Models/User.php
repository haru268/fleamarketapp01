<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    
    protected $fillable = ['name', 'email', 'password'];

   
    protected $hidden = ['password', 'remember_token'];

  
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

   
    public function exhibitedProducts()
    {
        return $this->hasMany(Product::class);
    }

   
    public function purchasedProducts()
    {
        return $this->hasMany(Product::class, 'buyer_id');
    }

   
    public function likedProducts()
    {
        return $this->belongsToMany(Product::class, 'likes', 'user_id', 'product_id')
                    ->withTimestamps();
    }

   
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

   
    public function userAddress()
    {
        return $this->hasOne(Address::class);
    }

   
    public function tradesAsSeller()  { return $this->hasMany(Trade::class,  'seller_id'); }
    public function tradesAsBuyer()   { return $this->hasMany(Trade::class,  'buyer_id'); }
    public function messages()        { return $this->hasMany(Message::class); }

   
    public function ratingsGiven()    { return $this->hasMany(Rating::class, 'rater_id'); }
    public function ratingsReceived() { return $this->hasMany(Rating::class, 'ratee_id'); }

    
    public function getAvatarUrlAttribute(): string
    {
        return $this->profile_image
            ? asset('storage/' . $this->profile_image)
            : asset('images/default-user.png');
    }

    /**
     * 取引相手から受け取った評価の平均（小数 1 桁）
     * @example {{ $user->avg_rating }}  // 4.2
     */
    public function getAvgRatingAttribute(): ?float
    {
        $avg = $this->ratingsReceived()->avg('score');
        return is_null($avg) ? null : round($avg, 1);
    }
}
