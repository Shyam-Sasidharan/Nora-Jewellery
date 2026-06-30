<?php

use App\Http\Controllers\Api\StorefrontController;
use Illuminate\Support\Facades\Route;

Route::prefix('storefront')->group(function () {
    Route::get('home', [StorefrontController::class, 'home']);
    Route::get('categories', [StorefrontController::class, 'categories']);
    Route::get('products', [StorefrontController::class, 'products']);
    Route::get('products/{product:slug}', [StorefrontController::class, 'product']);
    Route::get('about', [StorefrontController::class, 'about']);
    Route::get('gallery', [StorefrontController::class, 'gallery']);
    Route::get('contact', [StorefrontController::class, 'contactDetails']);
    Route::get('delivery', [StorefrontController::class, 'delivery']);
    Route::post('contact', [StorefrontController::class, 'contact']);
});
