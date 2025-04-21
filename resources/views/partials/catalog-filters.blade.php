<form id="filter-form" class="filters-form" method="GET" action="{{ route('catalog.filter') }}">
    <select name="brand" class="filter-select">
        <option value="">Бренд</option>
        <option value="Nike">Nike</option>
        <option value="Adidas">Adidas</option>
    </select>

    <select name="type" class="filter-select">
        <option value="">Тип</option>
        <option value="tshirt">Футболки</option>
        <option value="pants">Штани</option>
        <option value="hoodie">Худі</option>
    </select>

    <select name="sizez" class="filter-select">
        <option value="">Розмір</option>
        <option value="S">S</option>
        <option value="M">M</option>
        <option value="L">L</option>
    </select>

    <select name="color" class="filter-select">
        <option value="">Колір</option>
        <option value="black">Чорний</option>
        <option value="white">Білий</option>
    </select>

    <select name="gender" class="filter-select">
        <option value="">Стать</option>
        <option value="male">Чоловіча</option>
        <option value="female">Жіноча</option>
        <option value="kids">Дитяча</option>
    </select>

    <select name="sort" class="filter-select">
        <option value="">Сортувати</option>
        <option value="price_asc">Ціна ↑</option>
        <option value="price_desc">Ціна ↓</option>
        <option value="name_asc">Ім’я ↑</option>
        <option value="name_desc">Ім’я ↓</option>
    </select>

    <button type="button" id="reset-filters">Скинути фільтри</button>
</form>