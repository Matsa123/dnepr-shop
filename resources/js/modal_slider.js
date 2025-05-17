document.addEventListener('DOMContentLoaded', function () {
    const modalBody = document.getElementById('modal-body');

    const observer = new MutationObserver(() => {
        const slider = modalBody.querySelector('.slider');
        if (!slider) return;

        const slides = slider.querySelectorAll('img');
        const nav = modalBody.querySelector('.slider-nav');
        let currentIndex = 0;

        nav.innerHTML = ''; // Очищаем старые кнопки
        slides.forEach((_, index) => {
            const btn = document.createElement('button');
            btn.classList.add('slider-nav-btn'); // Добавляем класс для кнопки навигации
            btn.addEventListener('click', () => {
                currentIndex = index;
                updateSlider();
            });
            nav.appendChild(btn);
        });

        function updateSlider() {
            slider.style.transform = `translateX(-${currentIndex * 100}%)`;
            nav.querySelectorAll('button').forEach((btn, idx) => {
                btn.classList.toggle('active', idx === currentIndex);
            });
        }

        updateSlider(); // начальная отрисовка
    });

    observer.observe(modalBody, { childList: true, subtree: true });
});