<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockHistories extends Model
{
    protected $table = 'stock_histories';

    protected $guarded = [];

    public function products()
    {
        return $this->belongsTo(Products::class);
    }
}
