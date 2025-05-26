@extends('app')

@section('title', 'Про нас')

@push('styles')
    @vite(['resources/css/about_us.css'])
@endpush

@section('content')
<section class="about-section">
    <h2 class="about-title">Про нас</h2>
    <div class="about-container">
        <p class="about-text">
            <strong>VAKULA</strong> — це не просто інтернет-магазин одягу. Це місце, де стиль зустрічається зі свободою, а мода — з твоїм характером. Ми створили простір для тих, хто хоче бути собою, хто не боїться виділятись і жити яскраво.
        </p>
        <p class="about-text">
            Наша команда щодня шукає найсвіжіші тренди, щоб ти завжди був(була) на крок попереду. Тут ти знайдеш одяг, який розповість про тебе більше, ніж ти встигнеш сказати. Стрітстайл, кежуал, мінімалізм чи виклик – у нас є все для тих, хто вірить у власний стиль.
        </p>
        <p class="about-text final">
            Ми одягаємо нове покоління. Молоде, сміливе, стильне. <br>
            Ми — <strong>VAKULA. Стиль, що говорить замість тебе.</strong>
        </p>
    </div>
</section>
@endsection