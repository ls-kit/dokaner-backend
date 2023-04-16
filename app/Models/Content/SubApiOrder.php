<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class SubApiOrder extends Model
{
    protected $fillable = [
        'domain',
        'total_invoices',
        'total_orders',
    ];
}
