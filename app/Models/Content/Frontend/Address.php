<?php

namespace App\Models\Content\Frontend;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;

    protected $table = 'addresses';

    public $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = ['name', 'phone_one', 'phone_two', 'phone_three', 'address', 'area_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
