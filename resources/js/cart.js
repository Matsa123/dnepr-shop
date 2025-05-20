document.addEventListener('DOMContentLoaded', function () {
    const cartContent = document.getElementById('cart-content');
    const emptyCartMessage = document.getElementById('empty-cart-message');
    const totalPriceEl = document.getElementById('total-price');

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.item-total').forEach(el => {
            const text = el.textContent.replace('грн', '').trim();
            total += parseFloat(text) || 0;
        });
        if (totalPriceEl) totalPriceEl.textContent = Math.round(total) + ' грн';
    }

    function checkCartEmpty() {
        const items = document.querySelectorAll('.cart-item');
        const cartSummary = document.querySelector('.cart-summary');
        const checkoutForm = document.getElementById('checkout-form');
        const cartHeader = document.querySelector('.cart-header');

        if (items.length === 0) {
            if (emptyCartMessage) emptyCartMessage.style.display = 'block';
            if (cartSummary) cartSummary.style.display = 'none';
            if (checkoutForm) checkoutForm.style.display = 'none';
            if (cartHeader) cartHeader.style.display = 'none';
        } else {
            if (emptyCartMessage) emptyCartMessage.style.display = 'none';
            if (cartSummary) cartSummary.style.display = '';
            if (checkoutForm) checkoutForm.style.display = '';
            if (cartHeader) cartHeader.style.display = '';
        }
    }

    if (cartContent) {
        cartContent.addEventListener('click', function (e) {
            const item = e.target.closest('.cart-item');
            if (!item) return;
            const productId = item.dataset.id;

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

                const item = document.querySelector(`.cart-item[data-id="${id}"]`);
                if (item) {
                    item.querySelector('.quantity').textContent = data.quantity;
                    item.querySelector('.item-total').innerHTML = `${Math.round(data.itemTotal)} грн`;
                }

                if (totalPriceEl) totalPriceEl.textContent = Math.round(data.totalPrice) + ' грн';

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
                const item = document.querySelector(`.cart-item[data-id="${id}"]`);
                if (item) item.remove();

                if (totalPriceEl) totalPriceEl.textContent = Math.round(data.totalPrice) + ' грн';

                const cartCountEl = document.getElementById('cart-count');
                if (cartCountEl) cartCountEl.textContent = data.count;

                checkCartEmpty();
            });
    }

    updateTotal();
    checkCartEmpty();
});