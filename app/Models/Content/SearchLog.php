<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SearchLog extends Model
{

  use SoftDeletes;

  protected $table = 'search_logs';

  public $primaryKey = 'id';

  public $timestamps = true;

  protected $guarded = [];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
