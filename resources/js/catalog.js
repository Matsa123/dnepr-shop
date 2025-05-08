document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('filter-form');
    const resetBtn = document.getElementById('reset-filters');
    const productList = document.getElementById('product-list');
    const dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(dropdown => {
        const btn = dropdown.querySelector('.dropdown-btn');
        const defaultText = btn.getAttribute('data-default') || dropdown.getAttribute('data-name');
        btn.textContent = capitalizeFirstLetter(defaultText); // см. функцию ниже
    });

    // Функция для преобразования "brand" → "Brand"
    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
    // Установим значения по умолчанию (для сброса)
    dropdowns.forEach(dropdown => {
        const button = dropdown.querySelector('.dropdown-btn');
        button.setAttribute('data-default', button.textContent.trim());
    });

    // Обработка кастомных выпадающих фильтров
    dropdowns.forEach(dropdown => {
        const button = dropdown.querySelector('.dropdown-btn');
        const content = dropdown.querySelector('.dropdown-content');
        const items = dropdown.querySelectorAll('.dropdown-item');
        const filterName = dropdown.getAttribute('data-name');

        // Открытие и закрытие меню
        button.addEventListener('click', function () {
            content.classList.toggle('show');
        });

        // Закрытие при клике вне
        document.addEventListener('click', function (event) {
            if (!dropdown.contains(event.target)) {
                content.classList.remove('show');
            }
        });

        // Выбор пункта
        items.forEach(item => {
            item.addEventListener('click', function () {
                const value = this.dataset.value;
                const text = this.textContent;
                const defaultText = button.getAttribute('data-default');
                let input = form.querySelector(`input[name="${filterName}"]`);

                if (value === '') {
                    // Сброс фильтра
                    button.textContent = defaultText;
                    button.removeAttribute('data-value');
                    if (input) input.remove();
                } else {
                    // Применение фильтра
                    button.textContent = text;
                    button.setAttribute('data-value', value);

                    if (!input) {
                        input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = filterName;
                        form.appendChild(input);
                    }
                    input.value = value;
                }

                content.classList.remove('show');
                applyFilters();
            });
        });
    });

    // Применение фильтров (AJAX)
    function applyFilters() {
        const formData = new FormData(form);
        const query = new URLSearchParams(formData).toString();

        productList.innerHTML = '<p>Завантаження...</p>';

        fetch(`${form.action}?${query}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.text();
            })
            .then(html => {
                productList.innerHTML = html;
            })
            .catch(error => {
                console.error('Помилка під час завантаження:', error);
                productList.innerHTML = '<p style="color:red;">Помилка завантаження товарів.</p>';
            });
    }

    // Сброс фильтров
    resetBtn.addEventListener('click', () => {
        form.reset();

        // Удаляем все скрытые поля фильтров
        form.querySelectorAll('input[type="hidden"]').forEach(input => input.remove());

        // Возвращаем все кнопки фильтров к значению по умолчанию
        dropdowns.forEach(dropdown => {
            const button = dropdown.querySelector('.dropdown-btn');
            const defaultText = button.getAttribute('data-default');
            button.textContent = defaultText;
            button.removeAttribute('data-value');
        });

        applyFilters();
    });
});