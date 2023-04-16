<?php

namespace App\Models\Content;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
  use SoftDeletes;

  protected $table = 'invoices';

  public $primaryKey = 'id';

  public $timestamps = true;

  protected $guarded = [];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function invoiceItems()
  {
    return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id');
  }
}
