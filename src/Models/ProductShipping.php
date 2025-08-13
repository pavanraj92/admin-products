<?php

namespace admin\products\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductShipping extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    protected $casts = [
        'requires_shipping' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
