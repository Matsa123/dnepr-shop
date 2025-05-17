<form id="filter-form" class="filters-form" method="GET" action="{{ route('catalog.filter') }}">

    {{-- Бренд --}}
    <div class="dropdown" data-name="brand">
        <button type="button" class="dropdown-btn" data-default="Бренд">Бренд</button>
        <div class="dropdown-content">
            <div class="dropdown-item" data-value="">Не вибрано</div>
            @foreach($brands as $brand)
                <div class="dropdown-item" data-value="{{ $brand }}">{{ $brand }}</div>
            @endforeach
        </div>
    </div>

    {{-- Тип --}}
    <div class="dropdown" data-name="type">
        <button type="button" class="dropdown-btn" data-default="Тип">Тип</button>
        <div class="dropdown-content">
            <div class="dropdown-item" data-value="">Не вибрано</div>
            @foreach($types as $type)
                <div class="dropdown-item" data-value="{{ $type }}">{{ $type }}</div>
            @endforeach
        </div>
    </div>

    {{-- Розмір взуття --}}
    <div class="dropdown" data-name="shoe_size">
        <button type="button" class="dropdown-btn" data-default="Розмір взуття">Розмір взуття</button>
        <div class="dropdown-content">
            <div class="dropdown-item" data-value="">Не вибрано</div>
            @foreach($shoe_sizes as $size)
                <div class="dropdown-item" data-value="{{ $size }}">{{ $size }}</div>
            @endforeach
        </div>
    </div>
    <input type="hidden" name="shoe_size">

    {{-- Розмір одягу --}}
    <div class="dropdown" data-name="clothing_size">
        <button type="button" class="dropdown-btn" data-default="Розмір одягу">Розмір одягу</button>
        <div class="dropdown-content">
            <div class="dropdown-item" data-value="">Не вибрано</div>
            @foreach($clothing_sizes as $size)
                <div class="dropdown-item" data-value="{{ $size }}">{{ $size }}</div>
            @endforeach
        </div>
    </div>
    {{-- Колір --}}
    <div class="dropdown" data-name="color">
        <button type="button" class="dropdown-btn" data-default="Колір">Колір</button>
        <div class="dropdown-content">
            <div class="dropdown-item" data-value="">Не вибрано</div>
            @foreach($colors as $color)
                <div class="dropdown-item" data-value="{{ $color }}">{{ ucfirst($color) }}</div>
            @endforeach
        </div>
    </div>

    {{-- Стать --}}
    <div class="dropdown" data-name="gender">
        <button type="button" class="dropdown-btn" data-default="Стать">Стать</button>
        <div class="dropdown-content">
            <div class="dropdown-item" data-value="">Не вибрано</div>
            @foreach($genders as $gender)
                <div class="dropdown-item" data-value="{{ $gender }}">{{ ucfirst($gender) }}</div>
            @endforeach
        </div>
    </div>

    {{-- Сортування --}}
    <div class="dropdown" data-name="sort">
        <button type="button" class="dropdown-btn" data-default="Сортувати">Сортувати</button>
        <div class="dropdown-content">
            <div class="dropdown-item" data-value="">Не вибрано</div>
            <div class="dropdown-item" data-value="price_asc">Ціна ↑</div>
            <div class="dropdown-item" data-value="price_desc">Ціна ↓</div>
            <div class="dropdown-item" data-value="name_asc">Ім’я ↑</div>
            <div class="dropdown-item" data-value="name_desc">Ім’я ↓</div>
        </div>
    </div>
    <!-- Скрытые input'ы для фильтрации -->
    <input type="hidden" name="brand">
    <input type="hidden" name="type">
    <input type="hidden" name="color">
    <input type="hidden" name="gender">
    <input type="hidden" name="sort">

    <button type="button" id="reset-filters">Скинути фільтри</button>
</form>