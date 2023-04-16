<?php

namespace App\Models\Content;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
  use SoftDeletes;

  protected $table = 'products';

  public $primaryKey = 'id';

  public $timestamps = true;

  protected $guarded = [];

  protected $hidden = [
    'active',
    'user_id',
    'created_at',
    'updated_at',
    'deleted_at'
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function orderItems()
  {
    return $this->hasMany(OrderItem::class, 'product_id', 'id');
  }
}
