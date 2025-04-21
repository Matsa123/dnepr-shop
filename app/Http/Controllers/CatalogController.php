<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CatalogController extends Controller
{
    public function catalog()
    {
        $products = Product::latest()->get();
        return view('catalog', compact('products'));
    }

    public function filter(Request $request)
    {
        $query = Product::query();

        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('size')) {
            $query->whereJsonContains('sizes', $request->size);
        }

        if ($request->filled('color')) {
            $query->where('color', $request->color);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('sort')) {
            match ($request->sort) {
                'price_asc' => $query->orderBy('price'),
                'price_desc' => $query->orderByDesc('price'),
                'name_asc' => $query->orderBy('name'),
                'name_desc' => $query->orderByDesc('name'),
                default => null,
            };
        }

        $products = $query->get();

        if ($request->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('partials.product-list', compact('products'))->render();
        }

        return view('catalog', compact('products'));
    }
}
