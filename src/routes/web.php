<?php

use Illuminate\Support\Facades\Route;
use admin\products\Controllers\ProductManagerController;
use admin\products\Controllers\OrderManagerController;

Route::name('admin.')->middleware(['web', 'admin.auth'])->group(function () {
    Route::resource('products', ProductManagerController::class);
    Route::get('categories/get-children', [ProductManagerController::class, 'getChildren'])->name('categories.get-children');
    Route::get('categories/subcategories/{id}', [ProductManagerController::class, 'getSubcategories'])->name('categories.subcategories');
    Route::get('categories/{category}/nested-subcategories', [ProductManagerController::class, 'getSubcategoriesWithChildren'])->name('categories.nested_subcategories');

    //Orders
    Route::resource('orders', OrderManagerController::class);
    Route::post('orders/updateStatus', [OrderManagerController::class, 'updateStatus'])->name('orders.updateStatus');

    Route::delete('products/image/{id}', [ProductManagerController::class, 'deleteImage'])->name('products.image.delete');
});
