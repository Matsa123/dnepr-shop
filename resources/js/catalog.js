document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('filter-form');
    const selects = form.querySelectorAll('.filter-select');
    const resetBtn = document.getElementById('reset-filters');
    const productList = document.getElementById('product-list');

    function applyFilters() {
        const formData = new FormData(form);
        const query = new URLSearchParams(formData).toString();

        fetch(`${form.action}?${query}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(res => res.text())
            .then(html => {
                productList.innerHTML = '';
                productList.insertAdjacentHTML('beforeend', html);
            });
    }

    // Автофильтрация при изменении любого селекта
    selects.forEach(select => {
        select.addEventListener('change', applyFilters);
    });

    // Сброс фильтров
    resetBtn.addEventListener('click', () => {
        form.reset();
        applyFilters();
    });
});