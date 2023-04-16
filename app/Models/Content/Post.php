<?php

namespace App\Models\Content;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
  use SoftDeletes;

  // protected $table = 'posts';

  public $primaryKey = 'id';

  public $timestamps = true;

  protected $guarded = [];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function taxonomies()
  {
    return $this->belongsToMany(Taxonomy::class)->withTimestamps();
  }

  public function keywords()
  {
    return $this->belongsToMany(Keyword::class)->withTimestamps();
  }
}
