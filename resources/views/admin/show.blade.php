@extends('admin::admin.layouts.master')

@section('title', 'Products Management')

@section('page-title', 'Product Details')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.products.index') }}">Product Manager</a></li>
    <li class="breadcrumb-item active" aria-current="page">Product Details</li>
@endsection

@section('content')
    <!-- Container fluid  -->
    <div class="container-fluid">
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
                                                            @if(isset($product->tags) && $product->tags->count())
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
                                                            @if(isset($product->gallery) && is_array($product->gallery) && count($product->gallery))
                                                                @foreach($product->gallery as $img)
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
                                                            @if(!empty($product->featured_image_url))
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
    </div>
    <!-- End Container fluid  -->
@endsection
