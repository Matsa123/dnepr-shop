import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const burger = document.getElementById('burger');
    const mainNav = document.getElementById('mainNav');

    if (burger && mainNav) {
        burger.addEventListener('click', () => {
            mainNav.classList.toggle('open');
        });

        // Скрытие меню при уходе курсора
        mainNav.addEventListener('mouseleave', () => {
            mainNav.classList.remove('open');
        });
    }

    const toggleFiltersBtn = document.getElementById('toggle-filters-btn');
    const filtersBlock = document.getElementById('filters-block');

    if (toggleFiltersBtn && filtersBlock) {
        toggleFiltersBtn.addEventListener('click', () => {
            filtersBlock.classList.toggle('show');
        });
    }
});