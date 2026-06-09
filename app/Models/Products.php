<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';

    protected $guarded = [];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }
}
