<?php

namespace admin\products\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductPrice extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
