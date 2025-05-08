@forelse($products as $product)
    <div class="product-card">
        <img src="{{ $product->image }}" alt="{{ $product->name }}">
        <div class="product-card-content">
            <div class="product-info">
                <h3>{{ $product->name }}</h3>
                <p>{{ $product->price }} грн</p>
            </div>
            <a href="#" class="buy-now-btn">Купити зараз</a>
        </div>
    </div>
@empty
    <p>Нічого не знайдено</p>
@endforelse