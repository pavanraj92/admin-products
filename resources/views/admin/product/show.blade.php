{{-- filepath: c:\Users\admin\Documents\MyProjects\packages_project\laravel_packages_04082025\packages\admin\products\resources\views\admin\product\show.blade.php --}}
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
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="card-title mb-0">
                                {{ $product->name ?? 'N/A' }} @if ($product->sku)
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
                                                            {{ $product->seller?->full_name ?? ($product->seller?->name ?? 'N/A') }}
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
                                                    <p>{{ $product->primaryCategory?->title ?? ($product->primaryCategory?->name ?? 'N/A') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Sub Categories:</label>
                                                    <p>
                                                        @if ($product->categories && $product->categories->count())
                                                            {{ $product->categories->pluck('title')->implode(', ') }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Brand:</label>
                                                    <p>{{ $product->brand?->name ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            @if (class_exists(\admin\tags\Models\Tag::class))
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Tags:</label>
                                                    <p>
                                                        @if ($product->tags && $product->tags->count())
                                                            {{ $product->tags->pluck('name')->implode(', ') }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="card mt-3">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white font-bold">Pricing  @if(class_exists(\admin\product_inventories\Models\ProductInventory::class))& Inventory @endif</h5>
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
                                                    <p>${{ number_format($product->prices->regular_price ?? 0, 2) }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Sale Price:</label>
                                                    <p>${{ number_format($product->prices->sale_price ?? 0, 2) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Cost Price:</label>
                                                    <p>${{ number_format($product->prices->cost_price ?? 0, 2) }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Tax Class:</label>
                                                    <p>{!! config('product.constants.productTaxClass.' . ($product->prices->tax_class ?? ''), 'N/A') !!}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Tax Rate (%):</label>
                                                    <p>{{ $product->prices->tax_rate ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        @if(class_exists(\admin\product_inventories\Models\ProductInventory::class))
                                            @include('inventory::admin.show', ['product' => $product])
                                        @endif
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
                                                    <p>{{ $product->shipping->weight ? $product->shipping->weight . ' kg' : 'N/A' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Length:</label>
                                                    <p>{{ $product->shipping->length ? $product->shipping->length . ' cm' : 'N/A' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Width:</label>
                                                    <p>{{ $product->shipping->width ? $product->shipping->width . ' cm' : 'N/A' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Height:</label>
                                                    <p>{{ $product->shipping->height ? $product->shipping->height . ' cm' : 'N/A' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-bold">Shipping Class:</label>
                                            <p>{!! config('product.constants.productShippingClass.' . ($product->shipping->shipping_class ?? ''), 'N/A') !!}</p>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-bold">Requires Shipping:</label>
                                            <p>{{ $product->shipping->requires_shipping ?? false ? 'Yes' : 'No' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mt-3">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white font-bold">Images</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Product Gallery:</label>
                                            <p>
                                                @if ($product->images && $product->images->count())
                                                    @foreach ($product->images as $img)
                                                        <img src="{{ asset('storage/' . $img->gallery_image) }}"
                                                            alt="Gallery Image" class="img-thumbnail"
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
                                                @if (!empty($product->image_url))
                                                    {{-- <img src="{{ $product->image_url }}" alt="Featured Image"
                                                        class="img-thumbnail" style="max-width: 120px; max-height: 80px;">
                                                    <br> --}}
                                                    <small>{{ $product->image_url }}</small>
                                                @else
                                                    N/A
                                                @endif
                                            </p>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-bold">Image Alt Text:</label>
                                            <p>{{ $product->alt_text ?? 'N/A' }}</p>
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
                                            <p>
                                                {!! config('product.constants.aryStatusLabel.' . ($product->status ?? ''), 'N/A') !!}
                                            </p>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-bold">Featured Product:</label>
                                            <p>{{ $product->is_featured ?? false ? 'Yes' : 'No' }}</p>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-bold">Publish Date:</label>
                                            <p>
                                                {{ $product->published_at
                                                    ? $product->published_at->format(config('GET.admin_date_format') ?? 'Y-m-d')
                                                    : 'N/A' }}
                                            </p>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-bold">Created At:</label>
                                            <p>{{ $product->created_at ? $product->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s') : 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                     @include('admin::admin.seo_meta_data.view', ['seo' => $seo])
                                </div>
                                <div class="card mt-3">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white font-bold">Quick Actions</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-column">
                                            @admincan('products_manager_edit')
                                                <a href="{{ route('admin.products.edit', $product) }}"
                                                    class="btn btn-warning mb-2">
                                                    <i class="mdi mdi-pencil"></i> Edit Product
                                                </a>
                                            @endadmincan

                                            @admincan('products_manager_delete')
                                                <button type="button" class="btn btn-danger delete-btn delete-record"
                                                    title="Delete this record"
                                                    data-url="{{ route('admin.products.destroy', $product) }}"
                                                    data-redirect="{{ route('admin.products.index') }}"
                                                    data-text="Are you sure you want to delete this record?"
                                                    data-method="DELETE">
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
@endsection
