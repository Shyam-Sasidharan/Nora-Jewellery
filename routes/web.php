<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'react')->name('home');
Route::view('/about', 'react')->name('about');
Route::view('/categories', 'react')->name('categories');
Route::view('/collections', 'react')->name('products.index');
Route::view('/collections/{category}', 'react')->name('products.category');
Route::view('/jewellery/{product}', 'react')->name('products.show');
Route::view('/gallery', 'react')->name('gallery');
Route::view('/contact', 'react')->name('contact');
Route::view('/cart', 'react')->name('cart.index');
Route::view('/wishlist', 'react')->name('wishlist');
Route::view('/search', 'react')->name('search');
Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [CartController::class, 'placeOrder'])->name('checkout.place');
Route::get('/checkout/success/{order}', [CartController::class, 'success'])->name('checkout.success');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    Route::middleware('admin')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
        Route::delete('product-images/{image}', [ProductController::class, 'destroyImage'])->name('product-images.destroy');
        Route::patch('product-images/{image}/primary', [ProductController::class, 'makePrimary'])->name('product-images.primary');
        Route::resource('banners', BannerController::class);
        Route::resource('gallery', GalleryController::class)->parameters(['gallery' => 'gallery']);
        Route::get('about', [ContentController::class, 'editAbout'])->name('about.edit');
        Route::put('about', [ContentController::class, 'updateAbout'])->name('about.update');
        Route::get('contact-details', [ContentController::class, 'editContact'])->name('contact.edit');
        Route::put('contact-details', [ContentController::class, 'updateContact'])->name('contact.update');
        Route::get('delivery-settings', [ContentController::class, 'editDelivery'])->name('delivery.edit');
        Route::put('delivery-settings', [ContentController::class, 'updateDelivery'])->name('delivery.update');
        Route::patch('messages/{message}/read', [ContentController::class, 'markMessageRead'])->name('messages.read');
        Route::delete('messages/{message}', [ContentController::class, 'destroyMessage'])->name('messages.destroy');
    });
});
