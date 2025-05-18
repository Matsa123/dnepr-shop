<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('main');
});

Route::middleware('auth')->group(function () {
    Route::get('/products/manage', [ProductController::class, 'manage'])->name('manage');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::delete('/product-images/{id}', [ProductImageController::class, 'destroy'])->name('product_images.destroy');
    Route::delete('/products/{id}/image', [ProductController::class, 'deleteMainImage'])->name('product_main_image.delete');
});


Route::get('/catalog', [CatalogController::class, 'catalog'])->name('catalog');
Route::get('/catalog/filter', [CatalogController::class, 'filter'])->name('catalog.filter');

Route::get('/about_us', [AboutUsController::class, 'about_us'])->name('about_us');
Route::get('/contacts', [ContactsController::class, 'contacts'])->name('contacts');

Route::get('/product/preview/{id}', [ProductController::class, 'preview']);
// routes/web.php
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

require __DIR__ . '/auth.php';
