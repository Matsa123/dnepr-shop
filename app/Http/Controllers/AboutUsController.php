<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    public function about_us()
    {
        return view('about_us');
    }
}
