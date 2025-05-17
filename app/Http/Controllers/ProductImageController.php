<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ProductImageController extends Controller
{
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
