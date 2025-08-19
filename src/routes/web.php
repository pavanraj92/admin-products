<?php

use Illuminate\Support\Facades\Route;
use admin\products\Controllers\ProductManagerController;
use admin\products\Controllers\ReturnRefundManagerController;
use admin\products\Controllers\OrderManagerController;
use admin\products\Controllers\TransactionManagerController;
use admin\products\Controllers\ReportManagerController;

Route::name('admin.')->middleware(['web', 'admin.auth'])->group(function () {
    Route::resource('products', ProductManagerController::class);
    Route::get('categories/get-children', [ProductManagerController::class, 'getChildren'])->name('categories.get-children');
    Route::get('categories/subcategories/{id}', [ProductManagerController::class, 'getSubcategories'])->name('categories.subcategories');
    Route::get('categories/{category}/nested-subcategories', [ProductManagerController::class, 'getSubcategoriesWithChildren'])->name('categories.nested_subcategories');

    //Return and Refunds
    Route::resource('return_refunds', ReturnRefundManagerController::class) ->only(['index', 'show']);
    Route::post('return_refunds/updateStatus', [ReturnRefundManagerController::class, 'updateStatus'])->name('return_refunds.updateStatus');

    //Orders
    Route::resource('orders', OrderManagerController::class);
    Route::post('orders/updateStatus', [OrderManagerController::class, 'updateStatus'])->name('orders.updateStatus');

     // Transactions
    Route::resource('transactions', TransactionManagerController::class);

    // Report
    Route::get('reports', [ReportManagerController::class, 'index'])->name('reports.index');
    Route::delete('products/image/{id}', [ProductManagerController::class, 'deleteImage'])->name('products.image.delete');
});
