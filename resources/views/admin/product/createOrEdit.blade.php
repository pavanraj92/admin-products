@extends('admin::admin.layouts.master')

@section('title', 'Products Management')

@section('page-title', isset($product) ? 'Edit Product' : 'Create Product')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">
        <a href="{{ route('admin.products.index') }}">Manage Products</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">{{ isset($product) ? 'Edit Product' : 'Create Product' }}</li>
@endsection

@section('content')
    @if (Session::has('success') || Session::has('error'))
        <div class="alert alert-{{ Session::has('error') ? 'danger' : 'success' }} alert-dismissible fade show"
            role="alert">
            {{ Session::get('success') ?? Session::get('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="container-fluid">
        <!-- Start Product Content -->
        <div class="row">
            <div class="col-12">
                <div class="card card-body">
                    <form
                        action="{{ isset($product) ? route('admin.products.update', $product->id) : route('admin.products.store') }}"
                        method="POST" id="productForm" enctype="multipart/form-data">
                        @if (isset($product))
                            @method('PUT')
                        @endif
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <!-- Start Product Information -->
                                <div class="card mb-4" style="box-shadow: 0 4px 20px rgba(0, 123, 255, 0.15);">
                                    <h4 class="p-3 text-uppercase">Product Information</h4>
                                    <div class="card-body space-y-4">
                                        <div class="form-group">
                                            <label>Seller Name<span class="text-danger">*</span></label>
                                            <select name="seller_id" class="form-control select2" required>
                                                <option value="">Select Seller</option>
                                                @foreach ($sellers as $seller)
                                                    <option value="{{ $seller->id }}"
                                                        {{ ($product?->seller_id ?? old('seller_id')) == $seller->id ? 'selected' : '' }}>
                                                        {{ $seller->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('seller_id')
                                                <div class="text-danger validation-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="name" class="form-label">Product Name<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="name" id="name" class="form-control"
                                                value="{{ old('name', $product->name ?? '') }}"
                                                placeholder="Enter product name">
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="short_description" class="form-label">Short Description<span
                                                    class="text-danger">*</span></label>
                                            <textarea name="short_description" id="short_description" class="form-control" rows="2"
                                                placeholder="Brief product description...">{{ old('short_description', $product->short_description ?? '') }}</textarea>
                                            @error('short_description')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea name="description" id="description" class="form-control wysiwyg" rows="6"
                                                placeholder="Enter detailed product description...">{{ old('description', $product->description ?? '') }}</textarea>
                                            @error('description')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="primary_category_id" class="form-label">Primary Category<span
                                                        class="text-danger">*</span></label>
                                                <select id="primary_category_id" class="form-select select2"
                                                    name="primary_category_id">
                                                    <option value="">Select Category</option>
                                                    @foreach ($parentCategories as $id => $title)
                                                        <option value="{{ $id }}"
                                                            {{ ($product?->primary_category_id ?? old('seller_id')) == $id ? 'selected' : '' }}>
                                                            {{ $title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('primary_category_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="subcategory_id" class="form-label">Sub Category</label>
                                                <select id="subcategory_id" class="form-select select2"
                                                    name="subcategory_ids[]" multiple>
                                                    <option value="">Select Sub Category</option>
                                                </select>
                                                @error('subcategory_ids')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 pt-3">
                                                <label for="brand_id" class="form-label">Brand</label>
                                                <select name="brand_id" id="brand_id" class="form-class select2">
                                                    <option value="">Choose Brand...</option>
                                                    @foreach ($brands as $id => $name)
                                                        <option value="{{ $id }}"
                                                            {{ old('brand_id', is_object($product ?? null) ? $product->brand_id : '') == $id ? 'selected' : '' }}>
                                                            {{ $name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('brand_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row pt-3">
                                            <div class="col-md-6">
                                                <label for="sku" class="form-label">SKU<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="sku" id="sku" class="form-control"
                                                    value="{{ old('sku', $product->sku ?? '') }}"
                                                    placeholder="Product SKU">
                                                @error('sku')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="barcode" class="form-label">Barcode</label>
                                                <input type="text" name="barcode" id="barcode" class="form-control"
                                                    value="{{ old('barcode', $product->barcode ?? '') }}"
                                                    placeholder="Product barcode">
                                                @error('barcode')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            @if (class_exists(\admin\tags\Models\Tag::class))
                                                <div class="col-md-12 pt-3">
                                                    <label for="tag_ids" class="form-label">Tags</label>
                                                    <select name="tag_ids[]" id="tag_ids" class="form-class select2"
                                                        multiple>
                                                        <option value="" disabled>Choose Tags...</option>
                                                        @php
                                                            $selectedTagIds = old(
                                                                'tag_ids',
                                                                isset($product) && $product->tags
                                                                    ? $product->tags->pluck('id')->toArray()
                                                                    : [],
                                                            );
                                                        @endphp
                                                        @foreach ($tags as $id => $name)
                                                            <option value="{{ $id }}"
                                                                {{ in_array($id, $selectedTagIds) ? 'selected' : '' }}>
                                                                {{ $name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('tag_ids')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- End Product Information -->

                                <!-- Pricing & Inventory -->
                                <div class="card mb-4" style="box-shadow: 0 4px 20px rgba(0, 123, 255, 0.15);">
                                    <h4 class="p-3 text-uppercase">Pricing @if (class_exists(\admin\product_inventories\Models\ProductInventory::class))
                                            & Inventory
                                        @endif
                                    </h4>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label for="regular_price" class="form-label">Regular Price<span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="text" name="regular_price" id="regular_price"
                                                        class="form-control decimal-only"
                                                        value="{{ old('regular_price', $product->prices->regular_price ?? '') }}"
                                                        placeholder="0.00">
                                                    @error('regular_price')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="sale_price" class="form-label">Sale Price</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="text" name="sale_price" id="sale_price"
                                                        class="form-control decimal-only"
                                                        value="{{ old('sale_price', $product->prices->sale_price ?? '') }}"
                                                        placeholder="0.00">
                                                    @error('sale_price')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="cost_price" class="form-label">Cost Price</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="text" name="cost_price" id="cost_price"
                                                        class="form-control decimal-only"
                                                        value="{{ old('cost_price', $product->prices->cost_price ?? '') }}"
                                                        placeholder="0.00">
                                                    @error('cost_price')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="tax_class" class="form-label">Tax Class</label>
                                                <select name="tax_class" id="tax_class" class="form-class select2">
                                                    @php $taxClasses = config('product.constants.productTaxClass', []); @endphp
                                                    @foreach ($taxClasses as $value => $label)
                                                        <option value="{{ $value }}"
                                                            {{ old('stock_status', $product->prices->tax_class ?? 'standard') == $value ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('tax_class')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label for="tax_rate" class="form-label">Tax Rate (%)</label>
                                                <input type="text" name="tax_rate" id="tax_rate"
                                                    class="form-control decimal-only"
                                                    value="{{ old('tax_rate', $product->prices->tax_rate ?? '') }}"
                                                    placeholder="0.00">
                                                @error('tax_rate')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        @if (class_exists(\admin\product_inventories\Models\ProductInventory::class))
                                            @include('inventory::admin.createOrEdit', [
                                                'product' => $product ?? null,
                                            ])
                                        @endif
                                    </div>
                                </div>
                                <!-- End Pricing & Inventory -->

                                <!-- Start Shipping Information -->
                                <div class="card mb-4" style="box-shadow: 0 4px 20px rgba(0, 123, 255, 0.15);">
                                    <h4 class="p-3 text-uppercase">Shipping Information</h4>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label for="weight" class="form-label">Weight</label>
                                                <div class="input-group">
                                                    <input type="text" name="weight" id="weight"
                                                        class="form-control decimal-only"
                                                        value="{{ old('weight', $product->shipping->weight ?? '') }}"
                                                        placeholder="0.00">
                                                    <span class="input-group-text">kg</span>
                                                    @error('weight')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="length" class="form-label">Length</label>
                                                <div class="input-group">
                                                    <input type="text" name="length" id="length"
                                                        class="form-control decimal-only"
                                                        value="{{ old('length', $product->shipping->length ?? '') }}"
                                                        placeholder="0.00">
                                                    <span class="input-group-text">cm</span>
                                                    @error('length')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="width" class="form-label">Width</label>
                                                <div class="input-group">
                                                    <input type="text" name="width" id="width"
                                                        class="form-control decimal-only"
                                                        value="{{ old('width', $product->shipping->width ?? '') }}"
                                                        placeholder="0.00">
                                                    <span class="input-group-text">cm</span>
                                                    @error('width')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="height" class="form-label">Height</label>
                                                <div class="input-group">
                                                    <input type="text" name="height" id="height"
                                                        class="form-control decimal-only"
                                                        value="{{ old('height', $product->shipping->height ?? '') }}"
                                                        placeholder="0.00">
                                                    <span class="input-group-text">cm</span>
                                                    @error('height')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3 align-items-center">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="shipping_class" class="form-label">Shipping Class</label>
                                                    <select name="shipping_class" id="shipping_class"
                                                        class="form-class select2">
                                                        @foreach (config('product.constants.productShippingClass') as $class => $label)
                                                            <option value="{{ $class }}"
                                                                {{ old('shipping_class', $product?->shipping?->shipping_class ?? '') == $class ? 'selected' : '' }}>
                                                                {{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('shipping_class')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" name="requires_shipping"
                                                        id="requires_shipping" class="form-check-input" value="1"
                                                        {{ old('requires_shipping', $product->shipping->requires_shipping ?? false) ? 'checked' : '' }}>
                                                    <label for="requires_shipping" class="form-check-label">This product
                                                        requires shipping</label>
                                                    @error('requires_shipping')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Shipping Information -->

                            </div>
                            <div class="col-md-4">
                                <!-- Start Product Status -->
                                <div class="card mb-4" style="box-shadow: 0 4px 20px rgba(0, 123, 255, 0.15);">
                                    <h4 class="p-3 text-uppercase">Product Status</h4>
                                    <div class="card-body space-y-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="status" class="form-label">Status</label>
                                                    <select name="status" id="status" class="form-class select2">
                                                        @php $statuses = config('product.constants.aryStatus'); @endphp
                                                        @foreach ($statuses ?? [] as $value => $label)
                                                            <option value="{{ $value }}"
                                                                {{ old('status', $product->status ?? '') == $value ? 'selected' : '' }}>
                                                                {{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('status')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="form-group" style="margin-top: 10px;">
                                                    <label for="published_at" class="form-label">Publish Date</label>
                                                    <input type="text" name="published_at" id="published_at"
                                                        class="form-control"
                                                        value="{{ old('published_at', $product->published_at ?? '') }}"
                                                        placeholder="Click to select date" readonly>
                                                    <small class="text-muted">Click to open date picker</small>
                                                    @error('published_at')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="form-group form-check mt-2">
                                                    <input type="checkbox" name="is_featured" id="is_featured"
                                                        class="form-check-input" value="1"
                                                        {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}>
                                                    <label for="is_featured" class="form-check-label">Featured
                                                        Product</label>
                                                    @error('is_featured')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Product Status -->
                                <!-- Start Product Images -->
                                <div class="card mb-4" style="box-shadow: 0 4px 20px rgba(0, 123, 255, 0.15);">
                                    <h4 class="p-3 text-uppercase">Product Images</h4>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Product Gallery</label>
                                            <input type="file" name="gallery_images[]" id="gallery_images"
                                                class="form-control d-none" multiple accept="image/*">
                                            <div id="customGalleryBox" class="custom-upload-box text-center p-4 mb-2"
                                                style="border:2px dashed #007bff; border-radius:8px; cursor:pointer;">
                                                <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-2"></i>
                                                <div class="text-muted">Click or drag images here to upload</div>
                                            </div>
                                            <div id="galleryPreview" class="mt-2 d-flex flex-wrap gap-2">
                                                @if (isset($existingImages) && count($existingImages))
                                                    @foreach ($existingImages as $img)
                                                        <div class="image-thumb position-relative mb-2 p-1"
                                                            style="display:inline-block;">
                                                            <img src="{{ $img['url'] }}" alt="{{ $img['name'] }}"
                                                                style="max-width:109px; max-height:109px; border:1px solid #eee;">
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm remove-existing-image"
                                                                data-id="{{ $img['id'] }}"
                                                                style="position:absolute;top:2px;right:2px;">&times;</button>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="image_url" class="form-label">Featured Image URL</label>
                                            <input type="url" name="image_url" id="image_url" class="form-control"
                                                placeholder="https://example.com/image.jpg"
                                                value="{{ old('image_url', $product->image_url ?? '') }}">
                                            @error('image_url')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="alt_text" class="form-label">Image Alt Text</label>
                                            <input type="text" name="alt_text" id="alt_text" class="form-control"
                                                placeholder="Describe the image for accessibility"
                                                value="{{ old('alt_text', $product->alt_text ?? '') }}">
                                            @error('alt_text')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <!-- End Product Images -->

                                <!-- Start SEO Settings -->
                                <div class="card mb-4" style="box-shadow: 0 4px 20px rgba(0, 123, 255, 0.15);">
                                    @include('admin::admin.seo_meta_data.seo', ['seo' => $seo ?? null])
                                </div>
                                <!-- End SEO Settings -->
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"
                                id="saveBtn">{{ isset($product) ? 'Update' : 'Save' }}</button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back</a>
                            {{-- <a href="{{ route('admin.products.index') }}" class="btn btn-warning">Save as Draft</a> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End product Content -->
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@endpush

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    @include('product::admin.product.partials.scripts.editor')
    @include('product::admin.product.partials.scripts.form-validation-and-datepicker')
    @include('product::admin.product.partials.scripts.subcategory-dropdown')
    @include('product::admin.product.partials.scripts.gallery-upload')
    @include('product::admin.product.partials.scripts.remove-existing-image')
    @include('product::admin.product.partials.scripts.update-file-input')
@endpush
