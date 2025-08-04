<?php

use Illuminate\Support\Facades\Route;
use admin\products\Controllers\ProductManagerController;

Route::name('admin.')->middleware(['web', 'admin.auth'])->group(function () {
    Route::resource('products', ProductManagerController::class);
    Route::get('categories/{category}/nested-subcategories', [ProductManagerController::class, 'getSubcategoriesWithChildren'])->name('categories.nested_subcategories');
});
