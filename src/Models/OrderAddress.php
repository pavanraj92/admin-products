<?php

namespace admin\products\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderAddress extends Model
{
    use HasFactory, Sortable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_postcode',
        'shipping_country',
        'same_as_shipping_address',
        'delivery_name',
        'delivery_phone',
        'delivery_address',
        'delivery_city',
        'delivery_state',
        'delivery_postcode',
        'delivery_country',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
