@extends('app')

@section('title', 'Каталог')

@push('styles')
    @vite(['resources/css/catalog.css'])
@endpush

@section('content')
    <div class="catalog-page">
        <aside class="filters">
            @include('partials.catalog-filters')
        </aside>
        <main class="products" id="product-list">
            @include('partials.product-list', ['products' => $products])
        </main>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/catalog.js'])
@endpush