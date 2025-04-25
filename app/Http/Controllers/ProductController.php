<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use Storage;

class ProductController extends Controller
{

    public function manage(Request $request)
    {
        $product = null;
        if ($request->has('id')) {
            $product = Product::findOrFail($request->id);
        }
        if ($product && is_string($product->sizes)) {
            $decoded = json_decode($product->sizes, true);
            $product->sizes = is_array($decoded) ? $decoded : [];
        }

        return view('manage', [
            'product' => $product,
            'allProducts' => Product::all(['id', 'name']),
            'brands' => Product::distinct()->pluck('brand')->filter()->unique(),
            'colors' => Product::distinct()->pluck('color')->filter()->unique(),
            'types' => Product::distinct()->pluck('type')->filter()->unique(), // 👈 добавлено
        ]);
    }
    // Метод для сохранения товара
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'brand_select' => 'nullable|string',
            'brand_custom' => 'nullable|string',
            'gender' => 'nullable|string',
            'sizes' => 'nullable|array',
            'color' => 'nullable|string',
            'price' => 'required|integer',
            'type_select' => 'nullable|string',
            'type_custom' => 'nullable|string',
            'type' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data['brand'] = $request->input('brand_custom') ?: $request->input('brand_select');
        $data['type'] = $request->input('type_custom') ?: $request->input('type_select');
        $data['color'] = $request->input('color_custom') ?: $request->input('color');


        if (isset($data['sizes']) && is_array($data['sizes']) && in_array('some_size', $data['sizes'])) {
            // Действия при наличии размера
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
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
            'sizes' => 'nullable|array',
            'color' => 'nullable|string',
            'price' => 'required|integer',
            'type_select' => 'nullable|string',
            'type_custom' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        $data['brand'] = $request->input('brand_custom') ?: $request->input('brand_select');
        $data['type'] = $request->input('type_custom') ?: $request->input('type_select');
        $data['color'] = $request->input('color_custom') ?: $request->input('color');

        if (isset($data['sizes']) && is_array($data['sizes']) && in_array('some_size', $data['sizes'])) {
            // Действия при наличии размера
        }

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::delete('public/' . $product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'path' => $path,
                    ]);
                }
            }
        }
        return redirect()->route('manage', ['id' => $product->id])
            ->with('success', 'Товар обновлён');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Удаление изображений товара
        if ($product->images) {
            foreach ($product->images as $image) {
                // Удаляем изображение из хранилища
                if (!empty($image->image) && Storage::disk('public')->exists($image->image)) {
                    Storage::disk('public')->delete($image->image);
                }
                // Удаляем запись из базы данных
                $image->delete();
            }
        }

        // Удаление основного изображения товара
        if (!empty($product->image) && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // Удаляем сам товар
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

}
