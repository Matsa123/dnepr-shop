document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('filter-form');
    const resetBtn = document.getElementById('reset-filters');
    const productList = document.getElementById('product-list');
    const dropdowns = document.querySelectorAll('.dropdown');
    const modal = document.getElementById('product-modal');
    const modalBody = document.getElementById('modal-body');
    const closeBtn = document.querySelector('.close-modal');

    // Инициализация всех Swiper-слайдеров в карточках (если ещё не инициализированы)
    function initializeSwipers() {
        document.querySelectorAll('.product-swiper').forEach(el => {
            if (el.swiper) return; // Уже инициализирован
            new Swiper(el, {
                loop: true,
                slidesPerView: 1,
                spaceBetween: 10,
                grabCursor: true,
                navigation: {
                    nextEl: el.querySelector('.swiper-button-next'),
                    prevEl: el.querySelector('.swiper-button-prev'),
                },
                pagination: {
                    el: el.querySelector('.swiper-pagination'),
                    clickable: true,
                },
            });
        });
    }

    // Инициализация Swiper в модальном окне (один слайдер)
    function initializeModalSwiper() {
        const slider = modalBody.querySelector('.swiper');
        if (slider && !slider.swiper) {
            new Swiper(slider, {
                loop: true,
                slidesPerView: 1,
                spaceBetween: 10,
                grabCursor: true,
                navigation: {
                    nextEl: slider.querySelector('.swiper-button-next'),
                    prevEl: slider.querySelector('.swiper-button-prev'),
                },
                pagination: {
                    el: slider.querySelector('.swiper-pagination'),
                    clickable: true,
                },
            });
        }
    }

    // Делегирование кликов по карточкам товаров
    productList.addEventListener('click', (event) => {
        const target = event.target;

        // Если клик по стрелке слайдера — ничего не делаем
        if (
            target.classList.contains('swiper-button-prev') ||
            target.classList.contains('swiper-button-next') ||
            target.closest('.swiper-button-prev') ||
            target.closest('.swiper-button-next')
        ) {
            return;
        }

        const card = target.closest('.product-card');
        if (!card) return;
        event.preventDefault();

        const productId = card.dataset.id;
        if (!productId) return;

        fetch(`/product/preview/${productId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(res => res.text())
            .then(html => {
                modalBody.innerHTML = html;
                modal.classList.remove('hidden');
                initializeModalSwiper();
            })
            .catch(err => {
                console.error('Ошибка загрузки превью товара:', err);
            });
    });

    // AJAX применяем фильтры
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
                initializeSwipers(); // обновляем слайдеры для новых карточек
            })
            .catch(error => {
                console.error('Помилка під час завантаження:', error);
                productList.innerHTML = '<p style="color:red;">Помилка завантаження товарів.</p>';
            });
    }

    // Сабмит формы фильтра
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        applyFilters();
    });

    // Сброс фильтров
    resetBtn.addEventListener('click', () => {
        form.reset();
        form.querySelectorAll('input[type="hidden"]').forEach(input => input.remove());

        dropdowns.forEach(dropdown => {
            const button = dropdown.querySelector('.dropdown-btn');
            const defaultText = button.getAttribute('data-default');
            button.textContent = defaultText;
            button.removeAttribute('data-value');
        });

        applyFilters();
    });

    // Обработка выпадающих фильтров
    dropdowns.forEach(dropdown => {
        const button = dropdown.querySelector('.dropdown-btn');
        const content = dropdown.querySelector('.dropdown-content');
        const items = dropdown.querySelectorAll('.dropdown-item');
        const filterName = dropdown.getAttribute('data-name');
        const defaultText = button.getAttribute('data-default') || filterName;

        button.textContent = capitalizeFirstLetter(defaultText);

        button.addEventListener('click', function () {
            content.classList.toggle('show');
        });

        document.addEventListener('click', function (event) {
            if (!dropdown.contains(event.target)) {
                content.classList.remove('show');
            }
        });

        items.forEach(item => {
            item.addEventListener('click', function () {
                const value = this.dataset.value;
                const text = this.textContent;
                let input = form.querySelector(`input[name="${filterName}"]`);

                if (value === '') {
                    button.textContent = defaultText;
                    button.removeAttribute('data-value');
                    if (input) input.remove();
                } else {
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

    // Закрытие модального окна
    closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
    modal.addEventListener('click', (e) => {
        if (e.target === modal) modal.classList.add('hidden');
    });

    // Хелпер: первая буква заглавная
    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    // Инициализация при первом запуске
    initializeSwipers();
});