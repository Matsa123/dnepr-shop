@forelse($products as $product)
    <div class="product-card" data-id="{{ $product->id }}">
        <div class="swiper-container product-swiper product-slider-{{ $product->id }}">
            <div class="swiper-wrapper">
                {{-- Главное изображение --}}
                <div class="swiper-slide">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                </div>

                {{-- Дополнительные изображения --}}
                @foreach($product->images as $img)
                    <div class="swiper-slide">
                        <img src="{{ asset('storage/' . $img->image) }}" alt="{{ $product->name }}">
                    </div>
                @endforeach
            </div>
            <!-- Навигация -->
            <div class="swiper-button-prev swiper-button-prev-{{ $product->id }}"></div>
            <div class="swiper-button-next swiper-button-next-{{ $product->id }}"></div>
            <!-- Пагинация -->
            <div class="swiper-pagination swiper-pagination-{{ $product->id }}"></div>
        </div>

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

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
@endpush
