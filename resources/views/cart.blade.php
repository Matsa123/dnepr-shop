@extends('app')

@section('content')
    <div class="cart-container main_block">
        <h2>Ваше замовлення</h2>

        <div id="cart-content">
            @if(count($cart) > 0)
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Фото</th>
                            <th>Назва</th>
                            <th>Ціна</th>
                            <th>Кількість</th>
                            <th>Сума</th>
                            <th>Дії</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart as $item)
                            <tr data-id="{{ $item['id'] }}">
                                <td><img src="{{ asset('storage/' . $item['image']) }}" width="60"></td>
                                <td>{{ $item['name'] }}</td>
                                <td>{{ $item['price'] }} грн</td>
                                <td>
                                    <button class="qty-btn decrease">-</button>
                                    <span class="quantity">{{ $item['quantity'] }}</span>
                                    <button class="qty-btn increase">+</button>
                                </td>
                                <td><span class="item-total">{{ $item['price'] * $item['quantity'] }}</span> грн</td>
                                <td><button class="remove-btn">Видалити</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="cart-summary">
                    <p><strong>Загальна сума: </strong> <span id="total-price"></span> грн</p>
                </div>

                <form id="checkout-form" method="POST" action="{{ route('order.store') }}">
                    @csrf
                    <div class="form-group">
                        <label>Імʼя</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Телефон</label>
                        <input type="tel" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label>Коментар</label>
                        <textarea name="comment"></textarea>
                    </div>

                    <button type="submit">Оформити замовлення</button>
                </form>
                <p id="empty-cart-message" style="display:none;">Корзина порожня</p>
            @else
                <p id="empty-cart-message">Корзина порожня</p>
            @endif
        </div>
    </div>
@endsection

@vite(['resources/js/cart.js', 'resources/css/cart.css'])