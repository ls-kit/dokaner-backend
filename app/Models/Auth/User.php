<?php

namespace App\Models\Auth;

use App\Models\Auth\Traits\Attribute\UserAttribute;
use App\Models\Auth\Traits\Method\UserMethod;
use App\Models\Auth\Traits\Relationship\UserRelationship;
use App\Models\Auth\Traits\Scope\UserScope;
use App\Models\Content\Frontend\Address;
use App\Models\Content\Order;
use App\Models\Content\Product;
use App\Notifications\VerifyApiEmail;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User.
 */
class User extends BaseUser
{
  use UserAttribute,
    HasApiTokens,
    UserMethod,
    UserRelationship,
    UserScope,
    Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'email',
    'first_name',
    'last_name',
    'phone',
    'refund_method',
    'refund_credentials',
    'shipping_id',
    'billing_id',
    'avatar_type',
    'avatar_location',
    'password',
    'password_changed_at',
    'active',
    'confirmation_code',
    'confirmed',
    'timezone',
    'last_login_at',
    'last_login_ip',
    'to_be_logged_out'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  public function sendApiEmailVerificationNotification()
  {
    $this->notify(new VerifyApiEmail); // my notification
  }

  public function orders()
  {
    return $this->hasMany(Order::class);
  }

  public function address()
  {
    return $this->hasMany(Address::class, 'user_id', 'id');
  }

  public function shipping()
  {
    return $this->belongsTo(Address::class, 'shipping_id', 'id');
  }

  public function billing()
  {
    return $this->belongsTo(Address::class, 'billing_id', 'id');
  }

  public function wishlist()
  {
    return $this->belongsToMany(Product::class, 'wishlists', 'user_id', 'ItemId', 'id', 'ItemId')->wherePivot('deleted_at', null);
  }
}
