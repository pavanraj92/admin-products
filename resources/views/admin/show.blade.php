@extends('admin::admin.layouts.master')

@section('title', 'Products Management')

@section('page-title', 'Product Details')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">
        <a href="{{ route('admin.products.index') }}">Product Manager</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Product Details</li>
@endsection

@section('content')
    <!-- Container fluid  -->
    {{-- <div class="container-fluid">
        <!-- Start Email Content -->
        <div class="row">
            <div class="col-12">
                <div class="card">                    
                    <div class="table-responsive">
                         <div class="card-body">      
                            <div class="row">
                                <!-- Product Basic Info -->
                                <div class="col-md-6 mb-4">
                                    <div class="card shadow-sm">
                                        <div class="card-header bg-secondary text-white">Basic Information</div>
                                        <div class="card-body">
                                            <table class="table table-striped mb-0">
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">Seller Name</th>
                                                        <td>
                                                            @if (class_exists(\admin\users\Models\User::class))
                                                                {{ $product?->seller?->full_name ?? 'N/A' }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Product Name</th>
                                                        <td>{{ $product->name ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Short Description</th>
                                                        <td>{{ $product->short_description ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Full Description</th>
                                                        <td>{!! $product->description ?? 'N/A' !!}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Primary Category</th>
                                                        <td>{{ $product->primaryCategory->title ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Sub Category</th>
                                                        <td>{{ $product->subCategory->title ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Brand</th>
                                                        <td>{{ $product->brand->name ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Selected Categories</th>
                                                        <td>{{ isset($selectedCategories) ? implode(', ', $selectedCategories) : 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Tags</th>
                                                        <td>
                                                            @if (isset($product->tags) && $product->tags->count())
                                                                {{ $product->tags->pluck('name')->implode(', ') }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pricing & Inventory -->
                                <div class="col-md-6 mb-4">
                                    <div class="card shadow-sm">
                                        <div class="card-header bg-secondary text-white">Pricing & Inventory</div>
                                        <div class="card-body">
                                            <table class="table table-striped mb-0">
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">SKU</th>
                                                        <td>{{ $product->sku ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Barcode</th>
                                                        <td>{{ $product->barcode ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Regular Price</th>
                                                        <td>${{ number_format($product->regular_price, 2) ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Sale Price</th>
                                                        <td>${{ number_format($product->sale_price, 2) ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Cost Price</th>
                                                        <td>${{ number_format($product->cost_price, 2) ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Tax Class</th>
                                                        <td>{{ ucfirst($product->tax_class ?? 'none') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Tax Rate (%)</th>
                                                        <td>{{ $product->tax_rate ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Stock Quantity</th>
                                                        <td>{{ $product->stock_quantity ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Low Stock Threshold</th>
                                                        <td>{{ $product->low_stock_threshold ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Stock Status</th>
                                                        <td>
                                                            @php
                                                                $stockStatuses = [
                                                                    'in_stock' => 'In Stock',
                                                                    'out_of_stock' => 'Out of Stock',
                                                                    'on_backorder' => 'On Backorder'
                                                                ];
                                                            @endphp
                                                            {{ $stockStatuses[$product->stock_status ?? 'in_stock'] ?? 'N/A' }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Shipping & Dimensions -->
                                <div class="col-md-6 mb-4">
                                    <div class="card shadow-sm">
                                        <div class="card-header bg-secondary text-white">Shipping & Dimensions</div>
                                        <div class="card-body">
                                            <table class="table table-striped mb-0">
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">Weight</th>
                                                        <td>{{ $product->weight ? $product->weight . ' kg' : 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Length</th>
                                                        <td>{{ $product->length ? $product->length . ' cm' : 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Width</th>
                                                        <td>{{ $product->width ? $product->width . ' cm' : 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Height</th>
                                                        <td>{{ $product->height ? $product->height . ' cm' : 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Shipping Class</th>
                                                        <td>{{ ucfirst($product->shipping_class ?? 'no shipping class') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Requires Shipping</th>
                                                        <td>{{ ($product->requires_shipping ?? false) ? 'Yes' : 'No' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status & Dates -->
                                <div class="col-md-6 mb-4">
                                    <div class="card shadow-sm">
                                        <div class="card-header bg-secondary text-white">Status & Dates</div>
                                        <div class="card-body">
                                            <table class="table table-striped mb-0">
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">Status</th>
                                                        <td>
                                                            @php
                                                                $statuses = [
                                                                    'draft' => 'Draft',
                                                                    'published' => 'Published',
                                                                    'pending_review' => 'Pending Review',
                                                                    'private' => 'Private'
                                                                ];
                                                            @endphp
                                                            {{ $statuses[$product->status ?? 'draft'] ?? 'N/A' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Featured Product</th>
                                                        <td>{{ ($product->is_featured ?? false) ? 'Yes' : 'No' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Publish Date</th>
                                                        <td>{{ $product->published_at ? \Carbon\Carbon::parse($product->published_at)->format(config('GET.admin_date_time_format') ?? 'Y-m-d') : 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Created At</th>
                                                        <td>{{ $product->created_at ? $product->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s') : 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Updated At</th>
                                                        <td>{{ $product->updated_at ? $product->updated_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s') : 'N/A' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Images & SEO -->
                                <div class="col-md-12 mb-4">
                                    <div class="card shadow-sm">
                                        <div class="card-header bg-secondary text-white">Images & SEO</div>
                                        <div class="card-body">
                                            <table class="table table-striped mb-0">
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">Product Gallery</th>
                                                        <td>
                                                            @if (isset($product->gallery) && is_array($product->gallery) && count($product->gallery))
                                                                @foreach ($product->gallery as $img)
                                                                    <img src="{{ asset('storage/'.$img) }}" alt="Gallery Image" class="img-thumbnail" style="max-width: 100px; max-height: 80px; margin-right: 5px;">
                                                                @endforeach
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Featured Image URL</th>
                                                        <td>
                                                            @if (!empty($product->featured_image_url))
                                                                <img src="{{ $product->featured_image_url }}" alt="Featured Image" class="img-thumbnail" style="max-width: 120px; max-height: 80px;">
                                                                <br>
                                                                <small>{{ $product->featured_image_url }}</small>
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Image Alt Text</th>
                                                        <td>{{ $product->image_alt_text ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Meta Title</th>
                                                        <td>{{ $product->meta_title ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Meta Description</th>
                                                        <td>{{ $product->meta_description ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Meta Keywords</th>
                                                        <td>{{ $product->meta_keywords ?? 'N/A' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                             
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back</a> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End product Content -->
    </div> --}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="card-title mb-0">{{ $product->name ?? 'N/A' }} @if ($product->sku)
                                    - {{ $product->sku }}
                                @endif
                            </h4>
                            <div>
                                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary ml-2">
                                    Back
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white font-bold">Product Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Seller Name:</label>
                                                    <p>
                                                        @if (class_exists(\admin\users\Models\User::class))
                                                            {{ $product?->seller?->full_name ?? 'N/A' }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Product Name:</label>
                                                    <p>{{ $product->name ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Short Description:</label>
                                                    <p>{{ $product->short_description ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Full Description:</label>
                                                    <p>{!! $product->description ?? 'N/A' !!}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Primary Category:</label>
                                                    <p>{{ $product->primaryCategory->title ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Sub Category:</label>
                                                    <p>{{ $product->subCategory->title ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Brand:</label>
                                                    <p>{{ $product->brand->name ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Selected Categories:</label>
                                                    <p>{{ isset($selectedCategories) ? implode(', ', $selectedCategories) : 'N/A' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-weight-bold">Tags:</label>
                                            <p>
                                                @if (isset($product->tags) && $product->tags->count())
                                                    {{ $product->tags->pluck('name')->implode(', ') }}
                                                @else
                                                    N/A
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mt-3">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white font-bold">Pricing & Inventory</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">SKU:</label>
                                                    <p>{{ $product->sku ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Barcode:</label>
                                                    <p>{{ $product->barcode ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Regular Price:</label>
                                                    <p>${{ number_format($product->regular_price, 2) ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Sale Price:</label>
                                                    <p>${{ number_format($product->sale_price, 2) ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Cost Price:</label>
                                                    <p>${{ number_format($product->cost_price, 2) ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Tax Class:</label>
                                                    <p>{{ ucfirst($product->tax_class ?? 'none') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Tax Rate (%):</label>
                                                    <p>{{ $product->tax_rate ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Stock Quantity:</label>
                                                    <p>{{ $product->stock_quantity ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Low Stock Threshold:</label>
                                                    <p>{{ $product->low_stock_threshold ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Stock Status:</label>
                                                    @php
                                                        $stockStatuses = [
                                                            'in_stock' => 'In Stock',
                                                            'out_of_stock' => 'Out of Stock',
                                                            'on_backorder' => 'On Backorder',
                                                        ];
                                                    @endphp
                                                    <p>{{ $stockStatuses[$product->stock_status ?? 'in_stock'] ?? 'N/A' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mt-3">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white font-bold">Shipping & Dimensions</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Weight:</label>
                                                    <p>{{ $product->weight ? $product->weight . ' kg' : 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Length:</label>
                                                    <p>{{ $product->length ? $product->length . ' cm' : 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Width:</label>
                                                    <p>{{ $product->width ? $product->width . ' cm' : 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Height:</label>
                                                    <p>{{ $product->height ? $product->height . ' cm' : 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-bold">Shipping Class:</label>
                                            <p>{{ ucfirst($product->shipping_class ?? 'no shipping class') }}</p>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-bold">Requires Shipping:</label>
                                            <p>{{ $product->requires_shipping ?? false ? 'Yes' : 'No' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mt-3">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white font-bold">Images & SEO</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Product Gallery:</label>
                                            <p>
                                                @if (isset($product->gallery) && is_array($product->gallery) && count($product->gallery))
                                                    @foreach ($product->gallery as $img)
                                                        <img src="{{ asset('storage/' . $img) }}" alt="Gallery Image"
                                                            class="img-thumbnail"
                                                            style="max-width: 100px; max-height: 80px; margin-right: 5px;">
                                                    @endforeach
                                                @else
                                                    N/A
                                                @endif
                                            </p>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-bold">Featured Image URL:</label>
                                            <p>
                                                @if (!empty($product->featured_image_url))
                                                    <img src="{{ $product->featured_image_url }}" alt="Featured Image"
                                                        class="img-thumbnail" style="max-width: 120px; max-height: 80px;">
                                                    <br>
                                                    <small>{{ $product->featured_image_url }}</small>
                                                @else
                                                    N/A
                                                @endif
                                            </p>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-bold">Image Alt Text:</label>
                                            <p>{{ $product->image_alt_text ?? 'N/A' }}</p>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-bold">Meta Title:</label>
                                            <p>{{ $product->meta_title ?? 'N/A' }}</p>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-bold">Meta Description:</label>
                                            <p>{{ $product->meta_description ?? 'N/A' }}</p>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-bold">Meta Keywords:</label>
                                            <p>{{ $product->meta_keywords ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white font-bold">Product Settings</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Status:</label>
                                            @php
                                                $statuses = [
                                                    'draft' => 'Draft',
                                                    'published' => 'Published',
                                                    'pending_review' => 'Pending Review',
                                                    'private' => 'Private',
                                                ];
                                            @endphp
                                            <p>
                                                <span class="badge badge-info">
                                                    {{ $statuses[$product->status ?? 'draft'] ?? 'N/A' }}
                                                </span>
                                            </p>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-bold">Featured Product:</label>
                                            <p>{{ $product->is_featured ?? false ? 'Yes' : 'No' }}</p>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-bold">Publish Date:</label>
                                            <p>{{ $product->published_at ? \Carbon\Carbon::parse($product->published_at)->format(config('GET.admin_date_time_format') ?? 'Y-m-d') : 'N/A' }}
                                            </p>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-bold">Created At:</label>
                                            <p>{{ $product->created_at ? $product->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s') : 'N/A' }}
                                            </p>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-bold">Updated At:</label>
                                            <p>{{ $product->updated_at ? $product->updated_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s') : 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mt-3">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white font-bold">Quick Actions</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning mb-2">
                                                <i class="mdi mdi-pencil"></i> Edit Product
                                            </a>

                                            @admincan('products_delete')
                                            <button type="button" class="btn btn-danger delete-btn delete-record"
                                            title="Delete this record"  
                                            data-url="{{ route('admin.products.destroy', $product) }}"
                                            data-text="Are you sure you want to delete this record?"
                                            data-method="DELETE"
                                                >
                                                <i class="mdi mdi-delete"></i> Delete Product
                                            </button>
                                            @endadmincan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Container fluid  -->
@endsection
