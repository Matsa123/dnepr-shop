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

        // Теперь можно безопасно делать pluck, потому что Laravel кастит JSON в массивы
        $shoe_sizes = Product::whereNotNull('shoe_sizes')
            ->get()
            ->pluck('shoe_sizes')   // массивы размеров
            ->flatten()             // "распаковываем" вложенные массивы
            ->unique()              // уникальные значения
            ->values();

        $clothing_sizes = Product::whereNotNull('clothing_sizes')
            ->get()
            ->pluck('clothing_sizes')
            ->flatten()
            ->unique()
            ->values();

        return view('catalog', compact(
            'products',
            'brands',
            'types',
            'colors',
            'genders',
            'clothing_sizes',
            'shoe_sizes'
        ));
    }
    private function getFilterData()
    {
        return [
            'brands' => Product::select('brand')->distinct()->pluck('brand'),
            'types' => Product::select('type')->distinct()->pluck('type'),
            'colors' => Product::select('color')->distinct()->pluck('color'),
            'genders' => Product::select('gender')->distinct()->pluck('gender'),
            'shoe_sizes' => Product::whereNotNull('shoe_sizes')
                ->get()
                ->pluck('shoe_sizes')
                ->flatten()
                ->unique()
                ->values(),
            'clothing_sizes' => Product::whereNotNull('clothing_sizes')
                ->get()
                ->pluck('clothing_sizes')
                ->flatten()
                ->unique()
                ->values(),
        ];
    }
    public function filter(Request $request)
    {
        $query = Product::query();

        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('color')) {
            $query->where('color', $request->color);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // ✅ Фильтрация по размерам обуви (shoe_size[])
        if ($request->filled('shoe_size')) {
            $shoeSizes = (array) $request->input('shoe_size');

            $query->whereNotNull('shoe_sizes')
                ->where(function ($q) use ($shoeSizes) {
                    foreach ($shoeSizes as $size) {
                        $q->orWhereJsonContains('shoe_sizes', (string) $size);
                    }
                });
        }

        // ✅ Фильтрация по размерам одежды (clothing_size[])
        if ($request->has('clothing_size') && is_array($request->clothing_size)) {
            foreach ($request->clothing_size as $size) {
                $query->whereJsonContains('clothing_sizes', $size);
            }
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

        // AJAX: только список
        if ($request->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('partials.product-list', compact('products'))->render();
        }

        // Возврат полной страницы
        return view('catalog', array_merge(['products' => $products], $this->getFilterData()));
    }
}
