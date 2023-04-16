<?php

namespace App\Models\Content;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
  use SoftDeletes;

  protected $table = 'orders';

  public $primaryKey = 'id';

  public $timestamps = true;

  protected $guarded = [];

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

  public function orderItems()
  {
    return $this->hasMany(OrderItem::class, 'order_id', 'id');
  }
}
