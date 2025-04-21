<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\ContactsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('main');
});
Route::get('/catalog', [CatalogController::class, 'catalog'])->name('catalog');
Route::get('/catalog/filter', [CatalogController::class, 'filter'])->name('catalog.filter');

Route::get('/about_us', [AboutUsController::class, 'about_us'])->name('about_us');
Route::get('/contacts', [ContactsController::class, 'contacts'])->name('contacts');