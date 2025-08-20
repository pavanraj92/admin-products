@extends('admin::admin.layouts.master')

@section('title', 'Orders Management')

@section('page-title', 'Order Details')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.orders.index') }}">Order Manager</a></li>
    <li class="breadcrumb-item active" aria-current="page">Order Details</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="card-title mb-0">
                            {{ $order->order_number ?? 'N/A' }} - {{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('F d, Y') : 'N/A' }}
                        </h4>
                        <div>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary ml-2">
                                Back
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header bg-primary">
                                    <h5 class="mb-0 text-white font-bold">Order Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Order Number:</label>
                                                <p>{{ $order->order_number ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Order Date:</label>
                                                <p> {{ $order->order_date ? $order->order_date->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s') : '—' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Status:</label>
                                                <p>
                                                    {!! $order->status ? config('product.constants.aryOrderStatusLabel')[$order->status] ?? 'N/A' : 'N/A' !!}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Created At:</label>
                                                <p>
                                                    {{ $order->created_at ? $order->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s') : '—' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header bg-primary">
                                    <h5 class="mb-0 text-white font-bold">Shipping & Delivery Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Shipping Name:</label>
                                                <p>{{ $order?->orderAddress?->shipping_name ?? 'N/A' }}</p>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Shipping Email:</label>
                                                <p>{{ $order?->orderAddress?->shipping_email ?? 'N/A' }}</p>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Shipping Phone:</label>
                                                <p>{{ $order?->orderAddress?->shipping_phone ?? 'N/A' }}</p>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Shipping Address:</label>
                                                <p>{{ $order?->orderAddress?->shipping_address ?? 'N/A' }}</p>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Shipping City:</label>
                                                <p>{{ $order?->orderAddress?->shipping_city ?? 'N/A' }}</p>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Shipping State:</label>
                                                <p>{{ $order?->orderAddress?->shipping_state ?? 'N/A' }}</p>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Shipping Zip:</label>
                                                <p>{{ $order?->orderAddress?->shipping_zip ?? 'N/A' }}</p>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Shipping Country:</label>
                                                <p>{{ $order?->orderAddress?->shipping_country ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Delivery Name:</label>
                                                <p>{{ $order?->orderAddress?->delivery_name ?? 'N/A' }}</p>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Delivery Email:</label>
                                                <p>{{ $order?->orderAddress?->delivery_email ?? 'N/A' }}</p>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Delivery Phone:</label>
                                                <p>{{ $order?->orderAddress?->delivery_phone ?? 'N/A' }}</p>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Delivery Address:</label>
                                                <p>{{ $order?->orderAddress?->delivery_address ?? 'N/A' }}</p>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Delivery City:</label>
                                                <p>{{ $order?->orderAddress?->delivery_city ?? 'N/A' }}</p>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Delivery State:</label>
                                                <p>{{ $order?->orderAddress?->delivery_state ?? 'N/A' }}</p>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Delivery Zip:</label>
                                                <p>{{ $order?->orderAddress?->delivery_zip ?? 'N/A' }}</p>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Delivery Country:</label>
                                                <p>{{ $order?->orderAddress?->delivery_country ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-primary">
                                    <h5 class="mb-0 text-white font-bold">User Details</h5>
                                </div>
                                <div class="card-body">
                                     <div class="form-group">
                                        <label class="font-weight-bold">User Name:</label>
                                        <p>
                                            @if (class_exists(\admin\users\Models\User::class))
                                                {{ $order->userWithTrashed?->full_name ?? 'N/A' }}
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold">User Email:</label>
                                        <p>
                                            @if (class_exists(\admin\users\Models\User::class))
                                                {{ $order->userWithTrashed?->email ?? 'N/A' }}
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold">User Phone:</label>
                                        <p>
                                            @if (class_exists(\admin\users\Models\User::class))
                                                {{ $order->userWithTrashed?->mobile ?? 'N/A' }}
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                          <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-primary">
                                    <h5 class="mb-0 text-white font-bold">Seller Details</h5>
                                </div>
                                <div class="card-body">
                                     <div class="form-group">
                                        <label class="font-weight-bold">Seller Name:</label>
                                        <p>
                                            @if (class_exists(\admin\users\Models\User::class))
                                                {{ $order->sellerWithTrashed?->full_name ?? 'N/A' }}
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold">Seller Email:</label>
                                        <p>
                                            @if (class_exists(\admin\users\Models\User::class))
                                                {{ $order->sellerWithTrashed?->email ?? 'N/A' }}
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold">Seller Phone:</label>
                                        <p>
                                            @if (class_exists(\admin\users\Models\User::class))
                                                {{ $order->sellerWithTrashed?->mobile ?? 'N/A' }}
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary">
                <h5 class="mb-0 text-white font-bold">Order Items</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th>S. No.</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price (each)</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->orderItems as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item?->productWithTrashed?->name ?? 'N/A' }}</td>
                                    <td>{{ $item?->quantity ?? 0 }}</td>
                                    <td>{{ number_format($item?->price ?? 0, 2) }}</td>
                                    <td>{{ number_format($item?->total ?? 0, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No items found for this order.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
