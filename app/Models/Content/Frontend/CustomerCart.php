<?php

namespace App\Models\Content\Frontend;

use App\Models\Auth\User;
use App\Models\Content\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerCart extends Model
{
  use SoftDeletes;

  protected $table = 'customer_carts';

  public $primaryKey = 'id';

  public $timestamps = true;

  protected $guarded = [];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function product()
  {
    return $this->belongsTo(Product::class, 'ItemId', 'ItemId');
  }
}
