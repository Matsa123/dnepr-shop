import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const burger = document.getElementById('burger');
    const nav = document.getElementById('mainNav');

    burger.addEventListener('click', () => {
        nav.classList.toggle('active');
    });
});
