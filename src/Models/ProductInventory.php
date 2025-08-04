<?php

namespace admin\products\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductInventory extends Model
{
    use SoftDeletes;
    protected $table = 'product_inventory';

    protected $guarded = [];

    protected $casts = [
        'track_quantity' => 'boolean',
        'allow_backorders' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
