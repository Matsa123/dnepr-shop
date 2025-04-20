@extends('app')

@section('title', 'Головна')

@push('styles')
    @vite(['resources/css/main.css'])
@endpush

@section('content')
    <!-- Основний контент сторінки -->
    <h1>Контент сторінки</h1>
    <p>Текст або інші елементи...</p>
@endsection