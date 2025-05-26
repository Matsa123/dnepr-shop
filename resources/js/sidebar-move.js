// resources/js/sidebar-move.js

document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('.manage-container');
    const sidebar = document.getElementById('sidebar');

    function moveSidebar() {
        if (window.innerWidth < 1040) {
            container.appendChild(sidebar); // переместит в конец
        } else {
            container.insertBefore(sidebar, container.firstElementChild); // вернет в начало
        }
    }

    moveSidebar(); // запуск при загрузке
    window.addEventListener('resize', moveSidebar); // запуск при изменении размера окна
});