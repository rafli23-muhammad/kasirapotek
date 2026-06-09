<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogStock extends Model
{
    protected $table = 'log_stocks';

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
