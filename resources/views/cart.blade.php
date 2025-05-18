@extends('app')

@section('title', 'Корзина')

@section('content')
    <div class="cart-page">
        <h1>Ваш кошик</h1>

        @if(empty($cart))
            <p>Кошик порожній.</p>
        @else
            <ul>
                @foreach($cart as $id => $item)
                    <li>
                        <img src="{{ asset('storage/' . $item['image']) }}" width="50">
                        {{ $item['name'] }} — {{ $item['quantity'] }} шт. — {{ $item['price'] * $item['quantity'] }} грн
                        <form method="POST" action="{{ route('cart.remove') }}" style="display:inline;">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $id }}">
                            <button type="submit">Видалити</button>
                        </form>
                    </li>
                @endforeach
            </ul>

            <form method="POST" action="{{ route('cart.clear') }}">
                @csrf
                <button type="submit">Очистити кошик</button>
            </form>
        @endif
    </div>
@endsection