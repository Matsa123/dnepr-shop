import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
document.addEventListener('DOMContentLoaded', () => {
    const burger = document.getElementById('burger');
    const mainNav = document.getElementById('mainNav');

    burger.addEventListener('click', () => {
        mainNav.classList.toggle('open');
    });
    document.getElementById('toggle-filters-btn').addEventListener('click', function () {
        document.getElementById('filters-block').classList.toggle('show');
    });
});