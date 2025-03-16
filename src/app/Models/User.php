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
        'avatar_url',
        
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    
    public function exhibitedProducts()
    {
        return $this->hasMany(Product::class);
    }

    
    public function likedProducts()
    {
        return $this->belongsToMany(Product::class, 'likes', 'user_id', 'product_id');
    }

   
    public function purchasedProducts()
    {
        return $this->hasMany(Product::class, 'buyer_id');
    }

    
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    
    public function userAddress()
    {
        return $this->hasOne(Address::class);
    }


}
