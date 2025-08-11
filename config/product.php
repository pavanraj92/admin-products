<?php

return [
    'aryStatus' => [
        'draft' => 'Draft',
        'published' => 'Published',
        'pending_review' => 'Pending Review',
        'private' => 'Private',
    ],

    'refund_request_type' => [
        'return' => 'Return',
        'refund' => 'Refund',
        'replacement' => 'Replacement',
    ],

    'refundRequestTypeLabel' => [
        'return' => '<label class="badge badge-primary text-white">Return</label>',
        'refund' => '<label class="badge badge-success text-white">Refund</label>',
        'replacement' => '<label class="badge badge-warning text-white">Replacement</label>',
    ],

    'refund_status' => [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'processing' => 'Processing',
        'completed' => 'Completed',
    ],

    'refundStatusLabel' => [
        'pending'    => '<label class="badge bg-warning text-white">Pending</label>',
        'approved'   => '<label class="badge bg-success text-white">Approved</label>',
        'rejected'   => '<label class="badge bg-danger text-white">Rejected</label>',
        'processing' => '<label class="badge bg-info text-white">Processing</label>',
        'completed'  => '<label class="badge bg-primary text-white">Completed</label>',
    ],

    'refundMethod' => [
        'original_payment' => 'Original Payment',
        'store_credit' => 'Store Credit',
        'manual' => 'Manual',
    ],

    'aryOrderStatusLabel' => [
        'pending'    => '<label class="badge badge-warning text-white">Pending</label>',
        'processing' => '<label class="badge badge-primary">Processing</label>',
        'shipped'    => '<label class="badge badge-info">Shipped</label>',
        'delivered'  => '<label class="badge badge-success">Delivered</label>',
        'cancelled'  => '<label class="badge badge-danger">Cancelled</label>',
    ],

    'order_status' => [
        'pending' => 'Pending',
        'processing' => 'Processing',
        'shipped' => 'Shipped',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled',
    ],

    'transactionStatus' => [
        'success' => 'Success',
        'pending' => 'Pending',
        'failed' => 'Failed',
    ],

    'transactionStatusLabel' => [
        'success' => '<label class="badge badge-success text-white">Success</label>',
        'pending' => '<label class="badge badge-warning text-white">Pending</label>',
        'failed' => '<label class="badge badge-secondary text-white">Failed</label>',
    ]
];
