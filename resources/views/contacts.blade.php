@extends('app')

@section('title', 'Контакти')

@push('styles')
    @vite(['resources/css/contacts.css'])
@endpush

@section('content')
    <section class="contact-section">
        <h2 class="contact-title">Контакты</h2>
        <div class="contact-container">
            <p class="font_centr">
                Мы всегда рады вашим вопросам и предложениям!<br>
                Свяжитесь с нами удобным способом:
            </p>
            <ul class="contact-list font_centr">
                <li><strong>Телефон:</strong> <a href="tel:+380123456789">+38 (012) 345-67-89</a></li>
                <li><strong>Email:</strong> <a href="mailto:info@vakula.com">info@vakula.com</a></li>
                <li><strong>Адрес:</strong> г. Киев, ул. Молодёжная, 15</li>
                <li><strong>Рабочие часы:</strong> Пн-Пт: 10:00 - 19:00</li>
            </ul>
        </div>
    </section>
@endsection