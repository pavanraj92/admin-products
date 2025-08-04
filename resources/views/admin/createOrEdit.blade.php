@extends('admin::admin.layouts.master')

@section('title', 'Products Management')

@section('page-title', isset($product) ? 'Edit Product' : 'Create Product')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.products.index') }}">Manage Products</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ isset($product) ? 'Edit Product' : 'Create Product' }}</li>
@endsection

@section('content')
@if(Session::has('success') || Session::has('error'))
<div class="alert alert-{{ Session::has('error') ? 'danger' : 'success' }} alert-dismissible fade show" role="alert">
    {{ Session::get('success') ?? Session::get('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<div class="container-fluid">
    <!-- Start Product Content -->
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <form action="{{ isset($product) ? route('admin.products.update', $product->id) : route('admin.products.store') }}" method="POST" id="productForm" enctype="multipart/form-data">
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
                                        <label for="name" class="form-label">Product Name</label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" placeholder="Enter product name">
                                        @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="short_description" class="form-label">Short Description</label>
                                        <textarea name="short_description" id="short_description" class="form-control" rows="2" placeholder="Brief product description...">{{ old('short_description', $product->short_description ?? '') }}</textarea>
                                        @error('short_description')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea name="description" id="description" class="form-control wysiwyg" rows="6" placeholder="Enter detailed product description...">{{ old('description', $product->description ?? '') }}</textarea>
                                        @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="primary_category_id" class="form-label">Primary Category</label>
                                            <select id="primary_category_id" class="form-select select2" name="primary_category_id">
                                                <option value="">Select Category</option>
                                                @foreach($parentCategories as $id => $title)
                                                <option value="{{ $id }}">{{ $title }}</option>
                                                @endforeach
                                            </select>
                                            @error('primary_category_id')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="subcategory_id" class="form-label">Sub Category</label>
                                            <select id="subcategory_id" class="form-select select2" name="subcategory_ids[]" multiple>
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
                                                @foreach($brands as $id => $name)
                                                <option value="{{ $id }}" {{ old('brand_id', (is_object($product ?? null) ? $product->brand_id : '')) == $id ? 'selected' : '' }}>
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
                                            <label for="sku" class="form-label">SKU</label>
                                            <input type="text" name="sku" id="sku" class="form-control" value="{{ old('sku', $product->sku ?? '') }}" placeholder="Product SKU">
                                            @error('sku')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="barcode" class="form-label">Barcode</label>
                                            <input type="text" name="barcode" id="barcode" class="form-control" value="{{ old('barcode', $product->barcode ?? '') }}" placeholder="Product barcode">
                                            @error('barcode')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 pt-3">
                                            <label for="tag_ids" class="form-label">Tags</label>
                                            <select name="tag_ids[]" id="tag_ids" class="form-class select2" multiple>
                                                <option value="">Choose Tags...</option>
                                                @php
                                                $selectedTagIds = old('tag_ids', (isset($product) && $product->tags) ? $product->tags->pluck('id')->toArray() : []);
                                                @endphp
                                                @foreach($tags as $id => $name)
                                                <option value="{{ $id }}" {{ in_array($id, $selectedTagIds) ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('tag_ids')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Product Information -->

                            <!-- Pricing & Inventory -->
                            <div class="card mb-4" style="box-shadow: 0 4px 20px rgba(0, 123, 255, 0.15);">
                                <h4 class="p-3 text-uppercase">Pricing & Inventory</h4>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="regular_price" class="form-label">Regular Price</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" step="0.01" min="0" name="regular_price" id="regular_price" class="form-control" value="{{ old('regular_price', $product->prices->regular_price ?? '') }}" placeholder="0.00">
                                                @error('regular_price')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="sale_price" class="form-label">Sale Price</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" step="0.01" min="0" name="sale_price" id="sale_price" class="form-control" value="{{ old('sale_price', $product->prices->sale_price ?? '') }}" placeholder="0.00">
                                                @error('sale_price')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="cost_price" class="form-label">Cost Price</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" step="0.01" min="0" name="cost_price" id="cost_price" class="form-control" value="{{ old('cost_price', $product->prices->cost_price ?? '') }}" placeholder="0.00">
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
                                                <option value="none" {{ old('tax_class', $product->prices->tax_class ?? 'none') == 'none' ? 'selected' : '' }}>No Tax</option>
                                                <option value="standard" {{ old('tax_class', $product->prices->tax_class ?? '') == 'standard' ? 'selected' : '' }}>Standard</option>
                                                <option value="reduced" {{ old('tax_class', $product->prices->tax_class ?? '') == 'reduced' ? 'selected' : '' }}>Reduced</option>
                                                <option value="zero" {{ old('tax_class', $product->prices->tax_class ?? '') == 'zero' ? 'selected' : '' }}>Zero</option>
                                            </select>
                                            @error('tax_class')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="tax_rate" class="form-label">Tax Rate (%)</label>
                                            <input type="number" step="0.01" min="0" name="tax_rate" id="tax_rate" class="form-control" value="{{ old('tax_rate', $product->prices->tax_rate ?? '') }}" placeholder="0.00">
                                            @error('tax_rate')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="stock_quantity" class="form-label">Stock Quantity</label>
                                            <input type="number" min="0" name="stock_quantity" id="stock_quantity" class="form-control" value="{{ old('stock_quantity', $product->inventory->stock_quantity ?? '') }}" placeholder="0">
                                            @error('stock_quantity')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="low_stock_threshold" class="form-label">Low Stock Threshold</label>
                                            <input type="number" min="0" name="low_stock_threshold" id="low_stock_threshold" class="form-control" value="{{ old('low_stock_threshold', $product->inventory->low_stock_threshold ?? '') }}" placeholder="0">
                                            @error('low_stock_threshold')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="stock_status" class="form-label">Stock Status</label>
                                            <select name="stock_status" id="stock_status" class="form-class select2">
                                                <option value="in_stock" {{ old('stock_status', $product->inventory->stock_status ?? 'in_stock') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                                <option value="out_of_stock" {{ old('stock_status', $product->inventory->stock_status ?? '') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                                                <option value="on_backorder" {{ old('stock_status', $product->inventory->stock_status ?? '') == 'on_backorder' ? 'selected' : '' }}>On Backorder</option>
                                            </select>
                                            @error('stock_status')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
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
                                                <input type="number" step="0.01" min="0" name="weight" id="weight" class="form-control" value="{{ old('weight', $product->shipping->weight ?? '') }}" placeholder="0.00">
                                                <span class="input-group-text">kg</span>
                                                @error('weight')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="length" class="form-label">Length</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0" name="length" id="length" class="form-control" value="{{ old('length', $product->shipping->length ?? '') }}" placeholder="0.00">
                                                <span class="input-group-text">cm</span>
                                                @error('length')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="width" class="form-label">Width</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0" name="width" id="width" class="form-control" value="{{ old('width', $product->shipping->width ?? '') }}" placeholder="0.00">
                                                <span class="input-group-text">cm</span>
                                                @error('width')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="height" class="form-label">Height</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0" name="height" id="height" class="form-control" value="{{ old('height', $product->shipping->height ?? '') }}" placeholder="0.00">
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
                                                <select name="shipping_class" id="shipping_class" class="form-class select2">
                                                    @php
                                                    $shippingClasses = ['no shipping class', 'standard', 'express', 'overnight', 'free shipping'];
                                                    @endphp
                                                    @foreach($shippingClasses as $class)
                                                    <option value="{{ $class }}" {{ old('shipping_class', $product->shipping->shipping_class ?? '') == $class ? 'selected' : '' }}>
                                                        {{ ucfirst($class) }}
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
                                                <input type="checkbox" name="requires_shipping" id="requires_shipping" class="form-check-input" value="1" {{ old('requires_shipping', $product->shipping->requires_shipping ?? false) ? 'checked' : '' }}>
                                                <label for="requires_shipping" class="form-check-label">This product requires shipping</label>
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
                                                    @foreach($statuses ?? [] as $value => $label)
                                                    <option value="{{ $value }}" {{ old('status', $product->status ?? '') == $value ? 'selected' : '' }}>
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
                                                <input type="text" name="published_at" id="published_at" class="form-control" value="{{ old('published_at', $product->published_at ?? '') }}" placeholder="Click to select date" readonly>
                                                <small class="text-muted">Click to open date picker</small>
                                                @error('published_at')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group form-check mt-2">
                                                <input type="checkbox" name="is_featured" id="is_featured" class="form-check-input"
                                                    value="1"
                                                    {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}>
                                                <label for="is_featured" class="form-check-label">Featured Product</label>
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
                                        <div class="dropzone" id="productDropzone"></div>
                                        <input type="hidden" name="gallery_images" id="gallery_images">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="featured_image_url" class="form-label">Featured Image URL</label>
                                        <input type="url" name="featured_image_url" id="featured_image_url" class="form-control" placeholder="https://example.com/image.jpg" value="{{ old('featured_image_url', $product->featured_image_url ?? '') }}">
                                        @error('featured_image_url')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="image_alt_text" class="form-label">Image Alt Text</label>
                                        <input type="text" name="image_alt_text" id="image_alt_text" class="form-control" placeholder="Describe the image for accessibility" value="{{ old('image_alt_text', $product->image_alt_text ?? '') }}">
                                        @error('image_alt_text')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- End Product Images -->

                            <!-- Start SEO Settings -->
                            <div class="card mb-4" style="box-shadow: 0 4px 20px rgba(0, 123, 255, 0.15);">
                                <h4 class="p-3 text-uppercase">SEO Settings</h4>
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label for="meta_title" class="form-label">Meta Title</label>
                                        <input type="text" name="meta_title" id="meta_title" class="form-control" value="{{ old('meta_title', $product->seo->meta_title ?? '') }}" placeholder="Enter meta title">
                                        <small class="text-muted">Recommended: 50-60 characters</small>
                                        @error('meta_title')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="meta_description" class="form-label">Meta Description</label>
                                        <textarea name="meta_description" id="meta_description" class="form-control" rows="2" placeholder="Enter meta description">{{ old('meta_description', $product->seo->meta_description ?? '') }}</textarea>
                                        <small class="text-muted">Recommended: 150-160 characters</small>
                                        @error('meta_description')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                        <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" value="{{ old('meta_keywords', $product->seo->meta_keywords ?? '') }}" placeholder="Enter keywords separated by commas">
                                        @error('meta_keywords')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- End SEO Settings -->
        </div>
    </div>

    <div class="form-group">
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back</a>
        {{--<a href="{{ route('admin.products.index') }}" class="btn btn-warning">Save as Draft</a>--}}
        <button type="submit" class="btn btn-primary" id="saveBtn">{{ isset($product) ? 'Update' : 'Save'}}</button>
    </div>
    </form>
</div>
</div>
</div>
<!-- End product Content -->
</div>
@endsection

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{ asset('backend/custom.css') }}">

@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<!-- Initialize CKEditor -->
<script>
    let ckEditorInstance;
    ClassicEditor
        .create(document.querySelector('#description'))
        .then(editor => {
            ckEditorInstance = editor;

            // optional styling
            editor.ui.view.editable.element.style.minHeight = '250px';
            editor.ui.view.editable.element.style.maxHeight = '250px';
            editor.ui.view.editable.element.style.overflowY = 'auto';

            // Trigger validation on typing
            editor.model.document.on('change:data', () => {
                const descriptionVal = editor.getData();
                $('#description').val(descriptionVal);
                $('#description').trigger('keyup');
            });
        })
        .catch(error => {
            console.error(error);
        });
</script>

<!-- Dropzone Script -->
<script>
    $(document).ready(function() {
        // Initialize Select2 for any select elements with the class 'select2'
        $('.select2').select2();

        // Initialize DatePicker for published_at field
        $('#published_at').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: '0:+10', // Only future years
            showButtonPanel: true,
            closeText: 'Clear',
            currentText: 'Today',
            minDate: 0, // Disable dates before today
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            dayNamesShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            dayNamesMin: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
            beforeShow: function(input, inst) {
                // Add a clear button
                setTimeout(function() {
                    var buttonPane = $(input).datepicker("widget").find(".ui-datepicker-buttonpane");
                    if (buttonPane.length === 0) {
                        $(input).datepicker("widget").append('<div class="ui-datepicker-buttonpane ui-widget-content ui-helper-clearfix"><button type="button" class="ui-datepicker-current ui-state-default ui-priority-secondary ui-corner-all" onclick="clearDate()">Clear</button></div>');
                    }
                }, 1);
            }
        });

        // Function to clear the date
        window.clearDate = function() {
            $('#published_at').val('');
            $('#published_at').datepicker('hide');
        };

        $.validator.addMethod(
            "alphabetsOnly",
            function(value, element) {
                return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
            },
            "Please enter letters only"
        );
        //jquery validation for the form
        $('#productForm').validate({
            ignore: [],
            rules: {
                name: {
                    required: true,
                    minlength: 3,
                    maxlength: 100,
                    alphabetsOnly: true
                },
                short_description: {
                    required: false,
                    minlength: 3,
                    maxlength: 500
                },
                description: {
                    required: false,
                    minlength: 3
                },
                sku: {
                    required: false,
                    minlength: 0,
                    maxlength: 100
                },
                barcode: {
                    required: false,
                    minlength: 0,
                    maxlength: 100
                }
            },
            messages: {
                name: {
                    required: "Please enter a name",
                    minlength: "Name must be at least 3 characters long",
                    maxlength: "Maximum length of name must be 100 characters long"
                },
                short_description: {
                    required: "Please enter a short description",
                    minlength: "Short description must be at least 3 characters long",
                    maxlength: "Maximum length of short description must be 500 characters long"
                },
                description: {
                    required: "Please enter description",
                    minlength: "Description must be at least 3 characters long"
                }
            },
            submitHandler: function(form) {
                // Update textarea before submit
                if (ckEditorInstance) {
                    $('#description').val(ckEditorInstance.getData());
                }
                const $btn = $('#saveBtn');
                $btn.prop('disabled', true).text('Saving...');

                // Now submit
                form.submit();
            },
            errorElement: 'div',
            errorClass: 'text-danger custom-error',
            errorPlacement: function(error, element) {
                $('.validation-error').hide(); // hide blade errors
                if (element.attr("id") === "description") {
                    error.insertAfter($('.ck-editor')); // show below CKEditor UI
                } else {
                    error.insertAfter(element);
                }
            }
        });
        let newFiles = [];

        $('#imageInput').on('change', function(event) {
            const input = event.target;
            const preview = $('#imagePreview');
            preview.find('.new-image-container').remove(); // clear previous new previews
            newFiles = [];

            if (input.files) {
                Array.from(input.files).forEach((file, index) => {
                    newFiles.push(file);

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const html = `
                            <div class="image-container new-image-container" style="position:relative; display:inline-block;">
                                <img src="${e.target.result}" style="max-width:200px; max-height:120px; margin-right:5px;" />
                                <button type="button" class="btn btn-danger btn-sm remove-new-image" 
                                    style="position:absolute; top:0; right:0; border-radius:50%;" data-index="${index}">
                                    🗑
                                </button>
                            </div>`;
                        preview.append(html);
                    }
                    reader.readAsDataURL(file);
                });
            }
        });
    });
</script>

<!-- Subcategory Dropdown -->
<script>
    $(document).ready(function() {
        $('.select2').select2();

        $('#primary_category_id').on('change', function() {
            let primaryCategoryId = $(this).val();
            let url = "{{ route('admin.categories.nested_subcategories', ':id') }}";
            url = url.replace(':id', primaryCategoryId);

            $('#subcategory_id').empty();

            if (primaryCategoryId) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        $('#subcategory_id').append('<option value="">Select Sub Categories</option>');

                        response.forEach(function(subcategory) {
                            // Subcategory
                            $('#subcategory_id').append(
                                `<option value="${subcategory.id}">${subcategory.title}</option>`
                            );

                            // Sub-subcategories
                            if (subcategory.children && subcategory.children.length > 0) {
                                subcategory.children.forEach(function(subsub) {
                                    $('#subcategory_id').append(
                                        `<option value="${subsub.id}">-- ${subsub.title}</option>`
                                    );
                                });
                            }
                        });
                    },
                    error: function() {
                        $('#subcategory_id').append('<option value="">Failed to load</option>');
                    }
                });
            }
        });
    });
</script>


<!-- dropzone script for product images -->
<!-- This script handles the image upload functionality using Dropzone.js -->
<script>
    Dropzone.autoDiscover = false;

    const uploadedImages = [];

    const myDropzone = new Dropzone("#productDropzone", {
        url: "#", // No actual upload here
        autoProcessQueue: false,
        uploadMultiple: true,
        parallelUploads: 10,
        maxFilesize: 5,
        maxFiles: 10,
        acceptedFiles: "image/jpeg,image/png,image/webp",
        addRemoveLinks: true,
        dictDefaultMessage: "Drop files here or click to upload (Max 5MB)",

        init: function() {
            const dz = this;

            // Add existing images to dropzone
            existingImages.forEach(function(imageData) {
                // Create a mock file object for existing images
                const mockFile = {
                    name: imageData.name,
                    size: 0,
                    accepted: true,
                    dataURL: imageData.url,
                    imageData: imageData
                };

                // Add the file to dropzone
                dz.emit("addedfile", mockFile);
                dz.emit("thumbnail", mockFile, imageData.url);
                dz.emit("complete", mockFile);

                // Add to uploadedImages array
                uploadedImages.push({
                    id: imageData.id,
                    name: imageData.name,
                    url: imageData.url,
                    alt_text: imageData.alt_text,
                    is_existing: true
                });
            });

            // Update hidden input with existing images
            if (existingImages.length > 0) {
                document.getElementById('gallery_images').value = JSON.stringify(uploadedImages);
            }

            // On file added, convert it to base64 and store in a hidden input
            dz.on("addedfile", function(file) {
                // Skip if this is an existing image (already processed above)
                if (file.imageData && file.imageData.is_existing) {
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    uploadedImages.push({
                        name: file.name,
                        type: file.type,
                        size: file.size,
                        base64: event.target.result,
                        alt_text: '',
                        is_new: true
                    });
                    document.getElementById('gallery_images').value = JSON.stringify(uploadedImages);
                };
                reader.readAsDataURL(file);
            });

            dz.on("removedfile", function(file) {
                if (file.imageData && file.imageData.is_existing) {
                    // Remove existing image
                    const index = uploadedImages.findIndex(f => f.id === file.imageData.id);
                    if (index !== -1) {
                        uploadedImages.splice(index, 1);
                    }
                } else {
                    // Remove new image
                    const index = uploadedImages.findIndex(f => f.name === file.name);
                    if (index !== -1) {
                        uploadedImages.splice(index, 1);
                    }
                }
                document.getElementById('gallery_images').value = JSON.stringify(uploadedImages);
            });
        }
    });
</script>
@endpush