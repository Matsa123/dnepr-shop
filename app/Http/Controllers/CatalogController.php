<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CatalogController extends Controller
{
    public function catalog()
    {
        $products = Product::latest()->get();

        $brands = Product::select('brand')->distinct()->pluck('brand');
        $types = Product::select('type')->distinct()->pluck('type');
        $colors = Product::select('color')->distinct()->pluck('color');
        $genders = Product::select('gender')->distinct()->pluck('gender');
        $sizes = Product::pluck('sizes')->flatten()->unique()->values();

        return view('catalog', compact('products', 'brands', 'types', 'colors', 'genders', 'sizes'));
    }

    public function filter(Request $request)
    {
        $query = Product::query();

        // Добавление фильтров
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('color')) {
            $query->where('color', $request->color);
        }

        if ($request->filled('size')) {
            $query->whereJsonContains('sizes', $request->size);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Сортировка
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price');
                    break;
                case 'price_desc':
                    $query->orderByDesc('price');
                    break;
                case 'name_asc':
                    $query->orderBy('name');
                    break;
                case 'name_desc':
                    $query->orderByDesc('name');
                    break;
            }
        }

        $products = $query->get();

        if ($request->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('partials.product-list', compact('products'))->render();
        }

        return view('catalog', compact('products'));
    }
}
