<?php

namespace App\Models\Content\Frontend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailSubscriber extends Model
{
  use SoftDeletes;

  protected $table = 'email_subscribers';

  public $primaryKey = 'id';

  public $timestamps = true;

  protected $fillable = ['email'];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
