@extends('admin::admin.layouts.master')

@section('title', 'Orders Management')

@section('page-title', 'Orders Manager')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Orders Manager</li>
@endsection

@section('content')
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Page Content -->
        <div class="row">
            <div class="col-12">
                <div class="card card-body">
                    <h4 class="card-title">Filter</h4>
                    <form action="{{ route('admin.orders.index') }}" method="GET" id="filterForm">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="order_number">Order Number</label>
                                    <input type="text" name="order_number" id="order_number" class="form-control"
                                        value="{{ app('request')->query('order_number') }}" placeholder="Enter order number">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control select2">
                                        <option value="">All</option>
                                        @foreach(config('product.constants.order_status') as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ app('request')->query('status') == $key ? 'selected' : '' }}>
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
                                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary mt-4">Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">S. No.</th>
                                        <th>@sortablelink('order_number', 'Order Number', [], ['class' => 'text-dark'])</th>
                                        <th>@sortablelink('order_date', 'Order Date', [], ['class' => 'text-dark'])</th>
                                        <th>@sortablelink('status', 'Status', [], ['class' => 'text-dark'])</th>
                                        <th>@sortablelink('created_at', 'Created At', [], ['class' => 'text-dark'])</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($orders) && $orders->count() > 0)
                                        @php
                                            $i = ($orders->currentPage() - 1) * $orders->perPage() + 1;
                                        @endphp
                                        @foreach ($orders as $order)
                                            <tr>
                                                <th scope="row">{{ $i }}</th>
                                                <td>{{ $order->order_number }}</td>
                                                <td>{{ $order->order_date ? $order->order_date->format(config('GET.admin_date_format') ?? 'Y-m-d H:i:s') : '—' }}
                                                </td>
                                                <td>
                                                    <select class="form-control form-control-sm order-status-dropdown"
                                                        data-id="{{ $order->id }}" data-current="{{ $order->status }}">
                                                        @foreach (config('product.constants.order_status') as $key => $label)
                                                            <option value="{{ $key }}"
                                                                {{ $order->status == $key ? 'selected' : '' }}>
                                                                {{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    {{ $order->created_at ? $order->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s') : '—' }}
                                                </td>
                                                <td style="width: 10%;">
                                                    @admincan('product_orders_manager_view')
                                                        <a href="{{ route('admin.orders.show', $order) }}"
                                                            data-toggle="tooltip" data-placement="top" title="View this record"
                                                            class="btn btn-warning btn-sm"><i class="mdi mdi-eye"></i></a>
                                                    @endadmincan
                                                </td>
                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center">No records found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                            <!--pagination move the right side-->
                            @if ($orders->count() > 0)
                                {{ $orders->links('admin::pagination.custom-admin-pagination') }}
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End PAge Content -->
    </div>
    <!-- End Container fluid  -->
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).on('change', '.order-status-dropdown', function() {
            var $select = $(this);
            var orderId = $select.data('id');
            var newStatus = $select.val();
            var currentStatus = $select.data('current');

            if (newStatus === currentStatus) return;

            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to change the status to "' + newStatus + '"?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, change it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.orders.updateStatus') }}",
                        method: "POST",
                        data: {
                            id: orderId,
                            status: newStatus,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Success', response.message, 'success');
                                $select.data('current', newStatus);
                            } else {
                                Swal.fire('Error', response.message, 'error');
                                $select.val(currentStatus);
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'Failed to update status.', 'error');
                            $select.val(currentStatus);
                        }
                    });
                } else {
                    $select.val(currentStatus);
                }
            });
        });
    </script>
@endpush
