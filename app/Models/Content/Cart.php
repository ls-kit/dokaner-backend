<?php

namespace App\Models\Content;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'cart',
    ];

    public function user()
    {
      return $this->belongsTo(User::class);
    }
}
