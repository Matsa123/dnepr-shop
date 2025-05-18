<?php

namespace App\Http\Controllers;
use Mail;
use App\Mail\OrderCreated;
use App\Models\Customer;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'comment' => 'nullable|string',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->withErrors(['cart' => 'Корзина порожня']);
        }

        // Найти или создать клиента
        $customer = Customer::firstOrCreate(
            ['phone' => $validated['phone']],
            ['name' => $validated['name']]
        );

        // Общая сумма
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        // Создание заказа
        $order = $customer->orders()->create([
            'comment' => $validated['comment'],
            'total' => $total,
        ]);

        // Добавление товаров
        foreach ($cart as $item) {
            $order->items()->create([
                'product_id' => $item['id'],       // id товара
                'product_name' => $item['name'],
                'product_price' => $item['price'],
                'quantity' => $item['quantity'],
            ]);
        }

        // Очистка корзины
        session()->forget('cart');

        // Отправка письма
        Mail::to('d1chesb1ches@gmail.com')->send(new OrderCreated($order));

        return redirect()->route('cart')->with('success', 'Замовлення успішно оформлено!');
    }
}
