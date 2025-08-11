<?php

namespace admin\products\Models;

use admin\admin_auth\Models\Seo;
use admin\brands\Models\Brand;
use admin\categories\Models\Category;
use admin\tags\Models\Tag;
use admin\products\Models\ProductImage;
use admin\products\Models\ProductInventory;
use admin\products\Models\ProductPrice;
use admin\products\Models\ProductShipping;
use admin\users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Kyslik\ColumnSortable\Sortable;

class Product extends Model
{
    use SoftDeletes, Sortable;
    protected $guarded = [];

    protected $casts = [
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected $sortable = [
        'name',
        'status',
        'published_at',
        'created_at',
    ];
    protected $fillable = [
        'seller_id',
        'name',
        'slug',
        'short_description',
        'description',
        'primary_category_id',
        'subcategory_id',
        'brand_id',
        'sku',
        'barcode',
        'status',
        'is_featured',
        'published_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name, '_');
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name')) {
                $product->slug = Str::slug($product->name, '_');
            }
        });
    }

    // === One-to-One ===
    public function prices()
    {
        return $this->hasOne(ProductPrice::class);
    }

    public function inventory()
    {
        return $this->hasOne(ProductInventory::class);
    }

    public function shipping()
    {
        return $this->hasOne(ProductShipping::class);
    }

    // === One-to-Many ===
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // === Many-to-Many ===
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }

    // === BelongsTo (Single Foreign Keys) ===
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function primaryCategory()
    {
        return $this->belongsTo(Category::class, 'primary_category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    public function seller()
    {
        if (class_exists(\admin\users\Models\User::class)) {
            return $this->belongsTo(\admin\users\Models\User::class, 'seller_id');
        }
    }



    // === Optional: For hierarchical product structure ===
    // public function parent()
    // {
    //     return $this->belongsTo(self::class, 'parent_id');
    // }

    // public function children()
    // {
    //     return $this->hasMany(self::class, 'parent_id');
    // }

    public function scopeFilter($query, $name)
    {
        if ($name) {
            return $query->where('name', 'like', '%' . $name . '%');
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

    public static function getPerPageLimit(): int
    {
        return Config::has('get.admin_page_limit')
            ? Config::get('get.admin_page_limit')
            : 10;
    }


    public function seo()
    {
        return $this->hasOne(Seo::class, 'model_record_id')
            ->where('model_name', 'Product');
    }
}
