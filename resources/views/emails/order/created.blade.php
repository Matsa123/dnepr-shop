<h1>Нове замовлення</h1>

<p>Клієнт: {{ $order->customer->name }}</p>
<p>Телефон: {{ $order->customer->phone }}</p>
<p><strong>Коментар:</strong> {{ $order->comment }}</p>

<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th>Назва</th>
            <th>К-сть</th>
            <th>Ціна</th>
            <th>Сума</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($order->items as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td style="text-align: center;">{{ $item->quantity }}</td>
                <td style="text-align: right;">{{ $item->product_price }}</td>
                <td style="text-align: right;">{{ $item->quantity * $item->product_price }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<p><strong>Сума:</strong> {{ $order->total }} грн</p>