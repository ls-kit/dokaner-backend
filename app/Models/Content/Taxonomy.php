<?php

namespace App\Models\Content;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Taxonomy extends Model
{

  use SoftDeletes;

  protected $table = 'taxonomies';

  public $primaryKey = 'id';

  public $timestamps = true;

  protected $guarded = [];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function parent()
  {
    return $this->hasOne(self::class, 'otc_id', 'ParentId');
  }


  public function children()
  {
    return $this->hasMany(self::class, 'ParentId', 'otc_id');
  }
}
