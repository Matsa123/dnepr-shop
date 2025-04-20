<?php

use App\Http\Controllers\MobileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('main');
});