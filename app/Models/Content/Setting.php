<?php

namespace App\Models\Content;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Setting extends Model
{

  // use SoftDeletes;

  protected $table = 'settings';

  public $primaryKey = 'id';

  public $timestamps = true;

  protected $guarded = [];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public static function active_setting($active_key)
  {
    Setting::whereKey($active_key)->update([
      'active' => Carbon::now()->toDateTimeString(),
      'user_id' => auth()->user()->id,
    ]);
  }

  public static function save_settings(array $arras)
  {
    foreach ($arras as $key => $value) {
      Setting::updateOrCreate(
        ['key' => $key],
        [
          'value' => $value,
          'user_id' => auth()->user()->id,
        //   FOR SUB API DOMAINS
        //   'user_id' => 0,
        //   FOR SUB API DOMAINS
        ]
      );
    }
  }
}
