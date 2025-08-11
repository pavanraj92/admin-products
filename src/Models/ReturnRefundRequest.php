<?php

namespace admin\products\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\Config;

class ReturnRefundRequest extends Model
{
    use SoftDeletes, Sortable;

    protected $fillable = [
        'order_id',
        'product_id',
        'user_id',
        'seller_id',
        'request_type',
        'reason',
        'description',
        'status',
        'refund_amount',
        'refund_method',
        'refund_processed_at',
        'return_tracking_number',
        'return_shipping_carrier'
    ];

    protected $sortable = [
        'title',
        'parent_category_id',
        'slug',
        'status',
        'created_at',
    ];

    protected $casts = [
        'refund_processed_at' => 'datetime',
    ];

    public function scopeFilter($query, $keyword)
    {
        if ($keyword) {
            return $query->whereHas('user', function ($q) use ($keyword) {
                $q->whereRaw("CONCAT(first_name, ' ', last_name) like ?", ['%' . $keyword . '%']);
            })
            ->orWhereHas('product', function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%');
            });
        }
        return $query;
    }
    /**
     * filter by status
     */
    public function scopeFilterByStatus($query, $status)
    {
        if (!is_null($status)) {
            return $query->where('status', $status);
        }

        return $query;
    }

    public function scopeFilterByRequestType($query, $request_type)
    {
        if (!is_null($request_type)) {
            return $query->where('request_type', $request_type);
        }

        return $query;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        if (class_exists(\admin\users\Models\User::class)) {
            return $this->belongsTo(\admin\users\Models\User::class, 'user_id');
        }
    }

    public function seller()
    {
        if (class_exists(\admin\users\Models\User::class)) {
            return $this->belongsTo(\admin\users\Models\User::class, 'seller_id');
        }
    }

    public function order()
    {
        return $this->belongsTo(Order::class);

    }

    public static function getPerPageLimit(): int
    {
        return Config::has('get.admin_page_limit')
            ? Config::get('get.admin_page_limit')
            : 10;
    }
}
