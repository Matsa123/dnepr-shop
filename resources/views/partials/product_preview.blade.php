<div class="product-preview">
    <h2>{{ $product->name }}</h2>
    <div class="swiper product-swiper modal-slider-{{ $product->id }}">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
            </div>
            @foreach($product->images as $img)
                <div class="swiper-slide">
                    <img src="{{ asset('storage/' . $img->image) }}" alt="{{ $product->name }}">
                </div>
            @endforeach
        </div>

        <!-- Навигация -->
        <div class="swiper-button-prev swiper-prev-{{ $product->id }}"></div>
        <div class="swiper-button-next swiper-next-{{ $product->id }}"></div>

        <!-- Пагинация -->
        <div class="swiper-pagination swiper-pagination-{{ $product->id }}"></div>
    </div>

    <p>{{ $product->description }}</p>
    <p><strong>Ціна:</strong> {{ $product->price }} грн</p>
    <button class="buy-now-modal">Купити зараз</button>
</div>