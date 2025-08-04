<?php

namespace admin\products\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Products table fields
            'name' => 'required|string|max:100',
            'short_description' => 'required|string|max:500',
            'description' => 'nullable|string',
            'sku' => 'required|string|max:100|unique:products,sku',
            'barcode' => 'nullable|string|max:100',
            'status' => 'nullable|in:draft,published,pending_review,private',
            'is_featured' => 'nullable|boolean',
            'published_at' => 'nullable|date',
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
            'stock_status' => 'nullable|in:in_stock,out_of_stock,backordered',

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
            'gallery_images' => 'nullable|string',
            'image_url' => 'nullable|url',
            'alt_text' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required.',
            'name.max' => 'Product name cannot exceed 100 characters.',
            'short_description.max' => 'Short description cannot exceed 500 characters.',
            'sku.unique' => 'This SKU is already in use.',
            'brand_id.exists' => 'Selected brand does not exist.',
            'primary_category_id.exists' => 'Selected primary category does not exist.',
            'subcategory_ids.*.exists' => 'One or more selected subcategories do not exist.',
            'tag_ids.*.exists' => 'One or more selected tags do not exist.',
            'regular_price.numeric' => 'Regular price must be a valid number.',
            'sale_price.numeric' => 'Sale price must be a valid number.',
            'stock_quantity.integer' => 'Stock quantity must be a whole number.',
            'stock_status.in' => 'Invalid stock status selected.',
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
