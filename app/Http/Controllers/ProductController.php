<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function manage(Request $request)
    {
        $product = null;
        if ($request->has('id')) {
            $product = Product::findOrFail($request->id);
        }

        if ($product) {
            if (is_string($product->clothing_sizes)) {
                $decoded = json_decode($product->clothing_sizes, true);
                $product->clothing_sizes = is_array($decoded) ? $decoded : [];
            }
            if (is_string($product->shoe_sizes)) {
                $decoded = json_decode($product->shoe_sizes, true);
                $product->shoe_sizes = is_array($decoded) ? $decoded : [];
            }
        }

        // Группируем товары по полю type
        $productsByType = Product::all(['id', 'name', 'type'])
            ->groupBy('type');

        return view('manage', [
            'product' => $product,
            'productsByType' => $productsByType,
            'brands' => Product::distinct()->pluck('brand')->filter()->unique(),
            'colors' => Product::distinct()->pluck('color')->filter()->unique(),
            'types' => Product::distinct()->pluck('type')->filter()->unique(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'brand_select' => 'nullable|string',
            'brand_custom' => 'nullable|string',
            'gender' => 'nullable|string',
            'clothing_sizes' => 'nullable|array',
            'shoe_sizes' => 'nullable|array',
            'color' => 'nullable|string',
            'color_custom' => 'nullable|string',
            'price' => 'required|integer',
            'type_select' => 'nullable|string',
            'type_custom' => 'nullable|string',
            'type' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Обработка brand, type, color
        $data['brand'] = $request->input('brand_custom') ?: $request->input('brand_select');
        $data['type'] = $request->input('type_custom') ?: $request->input('type_select');
        $data['color'] = $request->input('color_custom') ?: $request->input('color');

        // Обеспечить, что размеры — это массивы, или пустые массивы
        $data['clothing_sizes'] = $data['clothing_sizes'] ?? [];
        $data['shoe_sizes'] = $data['shoe_sizes'] ?? [];


        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                ]);
            }
        }
        return redirect()->route('manage', ['id' => $product->id])
            ->with('success', 'Товар добавлен');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'brand_select' => 'nullable|string',
            'brand_custom' => 'nullable|string',
            'gender' => 'nullable|string',
            'clothing_sizes' => 'nullable|array',
            'shoe_sizes' => 'nullable|array',
            'color' => 'nullable|string',
            'color_custom' => 'nullable|string',
            'price' => 'required|integer',
            'type_select' => 'nullable|string',
            'type_custom' => 'nullable|string',
            'type' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data['brand'] = $request->input('brand') ?? ($request->input('brand_custom') ?: $request->input('brand_select'));
        $data['type'] = $request->input('type') ?? ($request->input('type_custom') ?: $request->input('type_select'));
        $data['color'] = $request->input('color_custom') ?: $request->input('color');

        $data['clothing_sizes'] = $data['clothing_sizes'] ?? [];
        $data['shoe_sizes'] = $data['shoe_sizes'] ?? [];

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        $product->update($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $uploadedImage) {
                $path = $uploadedImage->store('products', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path, // <== вот это поле обязательно!
                ]);
            }
        }

        return redirect()->route('manage', ['id' => $product->id])
            ->with('success', 'Товар оновлено');
    }
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->images) {
            foreach ($product->images as $image) {
                if (!empty($image->path) && Storage::disk('public')->exists($image->path)) {
                    Storage::disk('public')->delete($image->path);
                }
                $image->delete();
            }
        }

        if (!empty($product->image) && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('manage')->with('success', 'Товар удалён.');
    }

    public function deleteMainImage($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
            $product->image = null;
            $product->save();
        }

        return back()->with('success', 'Главное фото удалено');
    }

    public function preview($id)
    {
        $product = Product::findOrFail($id);

        if (request()->ajax()) {
            return view('partials.product_preview', compact('product'))->render();
        }

        abort(404);
    }
}