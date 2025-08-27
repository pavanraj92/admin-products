<?php

namespace admin\products\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, Sortable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'seller_id',
        'order_number',
        'order_date',
        'status',
        'commission_id  ',
        'commission_value',
        'commission_type',
        'coupon_id',
        'discount_value',
    ];

     /**
     * The attributes that should be sortable.
     */
    public $sortable = [
        'user_id',
        'order_number',
        'status',
        'created_at',
    ];

    protected $casts = [
        'order_date' => 'datetime',
    ];

    /**
     * filter by title
     */
    public function scopeFilter($query, $keyword)
    {
        if ($keyword) {
            return $query->where('order_number', 'like', '%' . $keyword . '%');
        }
        return $query;
    }
    /**
     * filter by status
     */
    public function scopeFilterByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        if (class_exists(\admin\users\Models\User::class)) {
            return $this->belongsTo(\admin\users\Models\User::class);
        }
    }

    public function seller()
    {
        if (class_exists(\admin\users\Models\User::class)) {
            return $this->belongsTo(\admin\users\Models\User::class, 'seller_id');
        }
    }

    public function orderAddress()
    {
        return $this->hasOne(OrderAddress::class);
    }

    public function sellerWithTrashed()
    {
        if (class_exists(\admin\users\Models\User::class)) {
            return $this->belongsTo(\admin\users\Models\User::class, 'seller_id')->withTrashed();
        }
    }

    public function userWithTrashed()
    {
        if (class_exists(\admin\users\Models\User::class)) {
            return $this->belongsTo(\admin\users\Models\User::class, 'user_id')->withTrashed();
        }
    }

    public static function getPerPageLimit(): int
    {
        return Config::has('get.admin_page_limit')
            ? Config::get('get.admin_page_limit')
            : 10;
    }

    public function getAmountAttribute()
    {
        // Sum the total of all order items for this order
        return $this->orderItems()->sum('total');
    }

    public function getCommissionAttribute(){
         $total = $this->orderItems->sum(function($item) {
            return $item->total; // uses the accessor
        });

        // Discount (from orders table)
        if (\Schema::hasColumn($this->getTable(), 'discount_value')) {
            $discount = $this->discount_value ?? 0;
        } else {
            $discount = 0;
        }

        $commission = 0;
        if (\Schema::hasColumn($this->getTable(), 'commission_value') && \Schema::hasColumn($this->getTable(), 'commission_type')) {
            $type = $this->commission_type ?? 'percentage'; // fallback
            if ($type === 'fixed') {
                $commission = $this->commission_value ?? 0; // fixed amount
            } elseif ($type === 'percentage') {
                $commission = ($total - $discount) * (($this->commission_value ?? 0) / 100); // percentage
            }
        }
        return $commission;
    }

    public function getGrandTotalAttribute()
    {
        $total = $this->orderItems->sum(function($item) {
            return $item->total; // uses the accessor
        });

        // Discount (from orders table)
        if (\Schema::hasColumn($this->getTable(), 'discount_value')) {
            $discount = $this->discount_value ?? 0;
        } else {
            $discount = 0;
        }

        $commission = 0;
        if (\Schema::hasColumn($this->getTable(), 'commission_value') && \Schema::hasColumn($this->getTable(), 'commission_type')) {
            $type = $this->commission_type ?? 'percentage'; // fallback
            if ($type === 'fixed') {
                $commission = $this->commission_value ?? 0; // fixed amount
            } elseif ($type === 'percentage') {
                $commission = ($total - $discount) * (($this->commission_value ?? 0) / 100); // percentage
            }
        }
        // Grand total = total - discount + commission
        return $total - $discount + $commission;
    }
}
