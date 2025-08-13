<?php

namespace admin\products\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Get the product ID from the route or input
        $productParam = $this->route('product');
        $productId = is_object($productParam) ? $productParam->id : $productParam;

        return [
            // Products table fields
            'seller_id' => 'required|exists:users,id',
            'name' => 'required|string|max:100',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $productId,
            'barcode' => 'nullable|string|max:100',
            'status' => 'nullable|in:draft,published,pending_review,private',
            'is_featured' => 'sometimes|boolean',
            'published_at' => 'nullable',
            'brand_id' => 'nullable|exists:brands,id',
            'primary_category_id' => 'nullable|exists:categories,id',
            'subcategory_ids' => 'nullable|array',
            'subcategory_ids.*' => 'exists:categories,id',


            // Product price table fields
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lte:regular_price',
            'cost_price' => 'nullable|numeric|min:0',
            'tax_class' => 'nullable|string|max:50',
            'tax_rate' => 'nullable|numeric|min:0|max:100',

            // Product inventory table fields
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'stock_status' => 'nullable|in:in_stock,out_of_stock,low_stock',

            // Product shipping table fields
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'shipping_class' => 'nullable|string|max:50',
            'requires_shipping' => 'nullable|boolean',

            // product_tags table fields
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:tags,id',

            // SEO fields
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',

            // product_images fields
            'gallery_image' => 'nullable|array',
            'gallery_image.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_url' => 'nullable|url',
            'alt_text' => 'nullable|string|max:255',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
