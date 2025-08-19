@extends('admin::admin.layouts.master')

@section('title', 'Products Management')
@section('page-title', 'Products Manager')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Products Manager</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Filter Card -->
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <h4 class="card-title">Filter</h4>
                <form action="{{ route('admin.products.index') }}" method="GET" id="filterForm">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="keyword">Product Name</label>
                                <input type="text" name="keyword" id="keyword" class="form-control"
                                    value="{{ request('keyword') }}" placeholder="Enter product name">
                            </div>
                        </div>
                         <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control select2">
                                        <option value="">All</option>
                                        @foreach (config('product.constants.aryStatus') as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ app('request')->query('status') === (string) $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                          <div class="col-auto mt-1 text-right">
                                <div class="form-group">
                                    <label for="created_at">&nbsp;</label>
                                    <button type="submit" form="filterForm" class="btn btn-primary mt-4">Filter</button>
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mt-4">Reset</a>
                                </div>
                            </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Products Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @admincan('products_manager_create')
                    <div class="text-right">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-3">Create New Product</a>
                    </div>
                    @endadmincan

                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>S. No.</th>
                                    <th>@sortablelink('name', 'Product Name', [], ['style' => 'color: #4F5467; text-decoration: none;'])</th>
                                    <th>@sortablelink('status', 'Status', [], ['style' => 'color: #4F5467; text-decoration: none;'])</th>
                                    <th>@sortablelink('published_at', 'Published At', [], ['style' => 'color: #4F5467; text-decoration: none;'])</th>
                                    <th>@sortablelink('created_at', 'Created At', [], ['style' => 'color: #4F5467; text-decoration: none;'])</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $index => $product)
                                <tr>
                                    <th scope="row">{{ ($products->firstItem() ?? 0) + $index }}</th>
                                    <td width="20%">
                                        <strong>
                                            {{ \Illuminate\Support\Str::limit($product->name, 100, '...') }}
                                        </strong>
                                        @if(!empty($product->short_description))
                                        <br>
                                        <small class="text-muted">
                                            @php
                                            $words = str_word_count(strip_tags($product->short_description), 1);
                                            $firstPart = implode(' ', array_slice($words, 0, 50));
                                            $secondPart = implode(' ', array_slice($words, 50));
                                            @endphp
                                            {{ ucfirst($firstPart) }}
                                            @if($secondPart)
                                            <br>
                                            {{ ucfirst($secondPart) }}
                                            @endif
                                        </small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                        $statusMap = [
                                        'published' => ['label' => 'Published', 'class' => 'badge-success'],
                                        'draft' => ['label' => 'Draft', 'class' => 'badge-secondary'],
                                        'pending_review' => ['label' => 'Pending Review', 'class' => 'badge-warning'],
                                        'private' => ['label' => 'Private', 'class' => 'badge-dark'],
                                        ];
                                        $currentStatus = $product->status;
                                        @endphp
                                        @if(isset($statusMap[$currentStatus]))
                                        <span class="badge {{ $statusMap[$currentStatus]['class'] }}">
                                            {{ $statusMap[$currentStatus]['label'] }}
                                        </span>
                                        @else
                                        <span class="badge badge-light">Unknown</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ optional($product->published_at)->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s') ?? '—' }}
                                    </td>
                                    <td>
                                        {{ optional($product->created_at)->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s') ?? '—' }}
                                    </td>
                                    <td style="width: 15%;">
                                        @admincan('products_manager_view')
                                        <a href="{{ route('admin.products.show', $product) }}"
                                            data-toggle="tooltip"
                                            title="View this record"
                                            class="btn btn-warning btn-sm"><i class="mdi mdi-eye"></i></a>
                                        @endadmincan
                                        @admincan('products_manager_edit')
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                            data-toggle="tooltip"
                                            title="Edit this record"
                                            class="btn btn-success btn-sm"><i class="mdi mdi-pencil"></i></a>
                                        @endadmincan
                                        @admincan('products_manager_delete')
                                        <a href="javascript:void(0)"
                                            data-toggle="tooltip"
                                            title="Delete this record"
                                            data-url="{{ route('admin.products.destroy', $product) }}"
                                            data-text="Are you sure you want to delete this record?"
                                            data-method="DELETE"
                                            class="btn btn-danger btn-sm delete-record"><i class="mdi mdi-delete"></i></a>
                                        @endadmincan
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @if ($products->count())
                        {{ $products->links('admin::pagination.custom-admin-pagination') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection