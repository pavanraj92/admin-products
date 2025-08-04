<?php

namespace admin\products\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductTag extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tag');
    }
}
