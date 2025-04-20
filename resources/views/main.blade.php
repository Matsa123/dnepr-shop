@extends('app')

@section('title', 'Головна')

@push('styles')
    @vite(['resources/css/main.css'])
@endpush



@section('content')
    <div class="main_container">Контент тут</div>
@endsection