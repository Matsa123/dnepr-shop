@extends('app')

@section('title', 'Каталог')

@push('styles')
    @vite(['resources/css/catalog.css'])
@endpush

@section('content')
    <div class="catalog-page">
        <button id="toggle-filters-btn">🔍 Фільтри</button>

        <aside class="filters" id="filters-block">
            @include('partials.catalog-filters')
        </aside>

        <main class="products" id="product-list">
            @include('partials.product-list', ['products' => $products])
        </main>

        @include('partials.product-view')
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/catalog.js'])
@endpush