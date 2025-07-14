<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'seller_id', 'buyer_id', 'status',
    ];

   
    public function product()  { return $this->belongsTo(Product::class); }
    public function seller()   { return $this->belongsTo(User::class, 'seller_id'); }
    public function buyer()    { return $this->belongsTo(User::class, 'buyer_id'); }
    public function messages() { return $this->hasMany(Message::class); }
    public function ratings()  { return $this->hasMany(Rating::class); }

   
    public function isParticipant(int $uid): bool
    {
        return $this->seller_id === $uid || $this->buyer_id === $uid;
    }

  
    public function opponent(int $uid): User
    {
        return $uid === $this->seller_id ? $this->buyer : $this->seller;
    }

    public function otherPartyId($uid)
    { return $uid==$this->seller_id ? $this->buyer_id : $this->seller_id; }
}
