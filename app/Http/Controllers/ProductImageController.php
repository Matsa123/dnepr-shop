<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;


class ProductImageController extends Controller
{
    public function uploadMain(Request $request, $id)
    {
        $request->validate([
            'image' => 'required|image|max:5120',
        ]);

        $product = Product::findOrFail($id);

        // Удаление старого изображения, если нужно
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $path = $request->file('image')->store('product_main_images', 'public');
        $product->image = $path;
        $product->save();

        return response()->json(['url' => asset('storage/' . $path)]);
    }

    public function deleteMain($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
            $product->image = null;
            $product->save();
        }

        return redirect()->back()->with('success', 'Главное изображение удалено.');
    }
    public function upload(Request $request, Product $product)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        $path = $request->file('image')->store('product_images', 'public');

        $productImage = $product->images()->create([
            'image' => $path,
        ]);

        return response()->json([
            'id' => $productImage->id,
            'url' => asset('storage/' . $path),
        ]);
    }
    public function destroy($id)
    {
        $image = ProductImage::findOrFail($id);

        // Удаление файла
        if ($image->image && Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
        }

        $image->delete();

        return back()->with('success', 'Изображение удалено');
    }
}
