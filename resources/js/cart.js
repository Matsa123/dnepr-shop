document.addEventListener('DOMContentLoaded', function () {
    const cartContent = document.getElementById('cart-content');
    const emptyCartMessage = document.getElementById('empty-cart-message');
    const totalPriceEl = document.getElementById('total-price');

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.item-total').forEach(el => {
            total += parseFloat(el.textContent) || 0;
        });
        if (totalPriceEl) totalPriceEl.textContent = total.toFixed(2);
    }

    function checkCartEmpty() {
        const rows = document.querySelectorAll('.cart-table tbody tr');
        const emptyCartMessage = document.getElementById('empty-cart-message');
        const cartTable = document.querySelector('.cart-table');
        const cartSummary = document.querySelector('.cart-summary');
        const checkoutForm = document.getElementById('checkout-form');

        if (rows.length === 0) {
            if (emptyCartMessage) emptyCartMessage.style.display = 'block';
            if (cartTable) cartTable.style.display = 'none';
            if (cartSummary) cartSummary.style.display = 'none';
            if (checkoutForm) checkoutForm.style.display = 'none';
        } else {
            if (emptyCartMessage) emptyCartMessage.style.display = 'none';
            if (cartTable) cartTable.style.display = '';
            if (cartSummary) cartSummary.style.display = '';
            if (checkoutForm) checkoutForm.style.display = '';
        }
    }

    if (cartContent) {
        cartContent.addEventListener('click', function (e) {
            const row = e.target.closest('tr');
            if (!row) return;
            const productId = row.dataset.id;

            if (e.target.classList.contains('increase')) {
                updateQuantity(productId, 'increase');
            } else if (e.target.classList.contains('decrease')) {
                updateQuantity(productId, 'decrease');
            } else if (e.target.classList.contains('remove-btn')) {
                removeItem(productId);
            }
        });
    }

    function updateQuantity(id, action) {
        fetch('/cart/update', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id, action })
        })
            .then(res => res.json())
            .then(data => {
                if (!data) return;

                const row = document.querySelector(`tr[data-id="${id}"]`);
                if (row) {
                    row.querySelector('.quantity').textContent = data.quantity;
                    row.querySelector('.item-total').textContent = data.itemTotal.toFixed(2);
                }

                if (totalPriceEl) totalPriceEl.textContent = data.totalPrice.toFixed(2);

                // Обновляем счётчик корзины
                const cartCountEl = document.getElementById('cart-count');
                if (cartCountEl) cartCountEl.textContent = data.count;

                checkCartEmpty();
            });
    }

    function removeItem(id) {
        fetch('/cart/remove', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id })
        })
            .then(res => res.json())
            .then(data => {
                const row = document.querySelector(`tr[data-id="${id}"]`);
                if (row) row.remove();

                if (totalPriceEl) totalPriceEl.textContent = data.totalPrice.toFixed(2);

                // Обновляем счётчик корзины
                const cartCountEl = document.getElementById('cart-count');
                if (cartCountEl) cartCountEl.textContent = data.count;

                checkCartEmpty();
            });
    }

    updateTotal();
    checkCartEmpty();
});