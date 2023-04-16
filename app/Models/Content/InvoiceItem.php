<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItem extends Model
{
  use SoftDeletes;

  protected $table = 'invoice_items';

  public $primaryKey = 'id';

  public $timestamps = true;

  protected $guarded = [];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function order_item()
  {
    return $this->belongsTo(OrderItem::class);
  }
}
