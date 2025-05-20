@extends('app')

@section('content')
    <div class="cart-container main_block">
        <h2 class="titlle_">Ваше замовлення</h2>

        <div id="cart-content">
            @if(count($cart) > 0)
                <div class="cart-header">
                    <div>Фото</div>
                    <div>Назва</div>
                    <div>Ціна</div>
                    <div>Кількість</div>
                    <div>Сума</div>
                    <div>Дії</div>
                </div>

                <div class="cart-items">
                    @foreach($cart as $item)
                        <div class="cart-item" data-id="{{ $item['id'] }}">
                            <div class="item-image">
                                <img src="{{ asset('storage/' . $item['image']) }}" width="60" alt="{{ $item['name'] }}">
                            </div>
                            <div class="item-name">
                                {{ $item['name'] }}
                            </div>
                            <div class="item-price">
                                {{ $item['price'] }}  грн
                            </div>
                            <div class="item-quantity">
                                <div class="inc_btn">
                                    <button type="button" class="qty-btn decrease">-</button>
                                    <span class="quantity">{{ $item['quantity'] }}</span>
                                    <button type="button" class="qty-btn increase">+</button>
                                </div>
                            </div>
                            <div class="item-total">
                                <span>{{ $item['price'] * $item['quantity'] }}</span> грн
                            </div>
                            <div class="item-remove">
                                <button type="button" class="remove-btn">Видалити</button>
                            </div>
                        </div>
                    @endforeach
                </div>

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

                <p id="empty-cart-message" style="display: none;">Корзина порожня</p>
            @else
                <p id="empty-cart-message">Корзина порожня</p>
            @endif
        </div>
    </div>
@endsection

@vite(['resources/js/cart.js', 'resources/css/cart.css'])