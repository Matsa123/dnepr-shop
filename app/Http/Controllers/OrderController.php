<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'comment' => 'nullable|string',
        ]);

        $cart = session()->get('cart', []);

        if (!$cart)
            return redirect()->route('cart')->with('error', 'Корзина порожня.');

        $customer = \App\Models\Customer::firstOrCreate(
            ['phone' => $data['phone']],
            ['name' => $data['name']]
        );

        \App\Models\Order::create([
            'customer_id' => $customer->id,
            'comment' => $data['comment'],
            'items' => json_encode($cart)
        ]);

        session()->forget('cart');

        return redirect()->route('cart')->with('success', 'Замовлення оформлено!');
    }
}
