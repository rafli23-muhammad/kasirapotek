<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionsItems extends Model
{
    protected $table = 'transaction_items';

    protected $guarded = [];

    public function transactions()
    {
        return $this->belongsTo(Transactions::class);
    }

    public function products()
    {
        return $this->belongsTo(Products::class);
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
