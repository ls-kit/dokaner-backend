<?php

namespace App\Models\Content;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class CouponUser extends Model
{

  protected $table = 'coupon_user';

  public $primaryKey = 'id';

  public $timestamps = true;

  protected $guarded = [];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function order()
  {
    return $this->belongsTo(Order::class);
  }

  public function coupon()
  {
    return $this->belongsTo(Coupon::class);
  }
}
