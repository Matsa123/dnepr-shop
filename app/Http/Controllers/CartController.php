<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = $request->input('quantity', 1); // получаем количество из запроса, по умолчанию 1

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);

        $totalCount = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'message' => 'Товар додано у кошик',
            'count' => $totalCount
        ]);
    }
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:products,id',
            'action' => 'required|string|in:increase,decrease',
        ]);

        $cart = session()->get('cart', []);

        if (!isset($cart[$request->id])) {
            return response()->json(['message' => 'Товар не знайдений'], 404);
        }

        if ($request->action === 'increase') {
            $cart[$request->id]['quantity'] += 1;
        } elseif ($request->action === 'decrease') {
            $cart[$request->id]['quantity'] = max(1, $cart[$request->id]['quantity'] - 1);
        }

        session()->put('cart', $cart);

        $totalCount = array_sum(array_column($cart, 'quantity'));

        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        return response()->json([
            'message' => 'Кількість оновлена',
            'count' => $totalCount,
            'quantity' => $cart[$request->id]['quantity'] ?? 0,
            'totalPrice' => $totalPrice,
            'itemTotal' => $cart[$request->id]['price'] * $cart[$request->id]['quantity'],
        ]);
    }
    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->id])) {
            unset($cart[$request->id]);
            session()->put('cart', $cart);
        }

        // Пересчёт общей суммы и количества
        $totalCount = array_sum(array_column($cart, 'quantity'));
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        return response()->json([
            'message' => 'Товар видалено',
            'count' => $totalCount,
            'totalPrice' => $totalPrice,
        ]);
    }
}