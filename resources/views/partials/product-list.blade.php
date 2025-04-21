@forelse($products as $product)
    <div class="product-card">
        <img src="{{ $product->image }}" alt="{{ $product->name }}">
        <h3>{{ $product->name }}</h3>
        <p>{{ $product->price }} грн</p>
    </div>
@empty
    <p>Нічого не знайдено</p>
@endforelse