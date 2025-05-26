@extends('app')

@section('title', 'Головна')

@push('styles')
    @vite(['resources/css/main.css', 'resources/js/main.js'])
@endpush

@section('content')
    <div class="video-slider-wrapper" style="position: relative; max-width: 900px; margin: auto;">
        <div class="swiper video-swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide" style="position: relative;">
                    <video preload="metadata" width="100%" height="auto" muted playsinline>
                        <source src="{{ asset('videos/beg.mp4') }}" type="video/mp4" />
                        Ваш браузер не підтримує відео.
                    </video>
                    <div class="slide-text">
                        <h2>Відкрий нові горизонти</h2>
                        <p>Кожен день — це шанс стати кращим.</p>
                        <a href="{{ route('catalog') }}" class="btn-primary">Дізнатися більше</a>
                    </div>
                </div>
                <div class="swiper-slide" style="position: relative;">
                    <video preload="metadata" width="100%" height="auto" muted playsinline>
                        <source src="{{ asset('videos/gora.mp4') }}" type="video/mp4" />
                        Ваш браузер не підтримує відео.
                    </video>
                    <div class="slide-text">
                        <h2>Підіймайся вище</h2>
                        <p>Долай труднощі та досягай цілей.</p>
                        <a href="{{ route('catalog') }}" class="btn-primary">Почати зараз</a>
                    </div>
                </div>
            </div>

            <!-- Кнопки навігації -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>

            <!-- Пагінація -->
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <!-- Блок із відгуками / цитатами -->
    <div id="video-quote" class="quote-text">
        Неймовірне відео! Надихає на нові звершення.
    </div>

    <!-- Галерея зображень -->
    <div class="image-gallery"
        style="max-width: 900px; margin: 2rem auto; display: flex; gap: 1rem; justify-content: center;">
        <img src="{{ asset('images/pexels-olly-863977.jpg') }}" alt="Природа"
            style="width: 30%; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.2);" />
        <img src="{{ asset('images/pexels-pixabay-163452.jpg') }}" alt="Успіх"
            style="width: 30%; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.2);" />
        <img src="{{ asset('images/pexels-pixabay-221210.jpg') }}" alt="Мотивація"
            style="width: 30%; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.2);" />
    </div>
    <div class="features" style="max-width: 900px; margin: 2rem auto; display: flex; gap: 2rem;">
        <div style="flex: 1; text-align: center;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="size-6 text-blue-600">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
            </svg>

            <h4>Чітка мета</h4>
            <p>Ми допоможемо вам сфокусуватись на важливому.</p>
        </div>
        <div style="flex: 1; text-align: center;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="w-6 h-6 text-blue-600">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0" />
            </svg>
            <h4>Підтримка</h4>
            <p>Ми з вами на кожному етапі.</p>
        </div>
        <div style="flex: 1; text-align: center;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="size-6 text-blue-600">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
            </svg>

            <h4>Розвиток</h4>
            <p>Рух тільки вперед.</p>
        </div>
    </div>
@endsection