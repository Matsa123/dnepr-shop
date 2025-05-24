
window.uploadMainImage = function (event) {
    const input = event.target;
    const file = input.files[0];
    const productId = document.querySelector('meta[name="product-id"]').getAttribute('content');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (!file || !productId) return;

    const formData = new FormData();
    formData.append('image', file);
    formData.append('_token', csrfToken);

    fetch(`/product-images/upload-main/${productId}`, {
        method: 'POST',
        body: formData
    })
        .then(response => {
            if (!response.ok) throw new Error('Ошибка сервера: ' + response.status);
            return response.json();
        })
        .then(data => {
            // Заменить текущее изображение
            let wrapper = document.querySelector('.main-image-wrapper');

            if (!wrapper) {
                wrapper = document.createElement('div');
                wrapper.className = 'main-image-wrapper';
                document.querySelector('.product-form').insertAdjacentElement('beforebegin', wrapper);
            }

            wrapper.innerHTML = `
            <img src="${data.url}" alt="Главное фото" class="preview-image">
            <form action="/product-main-image/delete/${productId}" method="POST"
                onsubmit="return confirm('Удалить главное изображение?')">
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn-delete-image">Удалить главное изображение</button>
            </form>
        `;
        })
        .catch(error => {
            console.error('Ошибка при загрузке главной фотографии:', error);
            alert('Не удалось загрузить главное изображение.');
        });

    input.value = '';
};
window.previewImages = function (event) {
    uploadAdditionalImage(event);
}

function uploadAdditionalImage(event) {
    const input = event.target;
    const files = input.files;
    const productId = document.querySelector('meta[name="product-id"]').getAttribute('content');

    console.log('Загрузка файлов:', files);
    console.log('productId:', productId);

    if (!files.length || !productId) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    Array.from(files).forEach(file => {
        const formData = new FormData();
        formData.append('image', file);
        formData.append('_token', csrfToken);

        fetch(`/product-images/upload/${productId}`, {
            method: 'POST',
            body: formData,
        })
            .then(response => {
                if (!response.ok) throw new Error('Ошибка сервера: ' + response.status);
                return response.json();
            })
            .then(data => {
                const container = document.querySelector('.image-thumbnails');
                const wrapper = document.createElement('div');
                wrapper.className = 'thumbnail-wrapper';

                wrapper.innerHTML = `
                <img src="${data.url}" alt="image" class="thumbnail">
                <form action="/product-images/${data.id}" method="POST" onsubmit="return confirm('Удалить это изображение?')">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn-delete-image">Удалить доп. фото</button>
                </form>
            `;
                container.appendChild(wrapper);
            })
            .catch(error => {
                console.error('Ошибка при загрузке изображения:', error);
                alert('Не удалось загрузить изображение.');
            });
    });

    input.value = '';
}
document.addEventListener('DOMContentLoaded', function () {
    const brandSelect = document.getElementById('brand_select');
    const brandCustom = document.getElementById('brand_custom');
    const brandFinal = document.getElementById('brand_final');

    const typeSelect = document.getElementById('type_select');
    const typeCustom = document.getElementById('type_custom');
    const typeFinal = document.getElementById('type_final');

    function isValueInSelect(select, value) {
        return Array.from(select.options).some(option => option.value === value);
    }

    function syncBrand() {
        if (brandSelect.value === 'custom') {
            brandCustom.style.display = 'block';
            brandFinal.value = brandCustom.value;
        } else {
            brandCustom.style.display = 'none';
            brandFinal.value = brandSelect.value;
        }
    }

    function syncType() {
        if (typeSelect.value === 'custom') {
            typeCustom.style.display = 'block';
            typeFinal.value = typeCustom.value;
        } else {
            typeCustom.style.display = 'none';
            typeFinal.value = typeSelect.value;
        }
        updateShoeSizeVisibility();
    }

    brandSelect.addEventListener('change', syncBrand);
    brandCustom.addEventListener('input', function () {
        if (brandSelect.value === 'custom') {
            brandFinal.value = brandCustom.value;
        }
    });

    typeSelect.addEventListener('change', syncType);
    typeCustom.addEventListener('input', function () {
        if (typeSelect.value === 'custom') {
            typeFinal.value = typeCustom.value;
            updateShoeSizeVisibility();
        }
    });

    const brandInitialValue = brandFinal.value;
    if (!isValueInSelect(brandSelect, brandInitialValue) && brandInitialValue) {
        brandSelect.value = 'custom';
        brandCustom.value = brandInitialValue;
        syncBrand();
    }

    const typeInitialValue = typeFinal.value;
    if (!isValueInSelect(typeSelect, typeInitialValue) && typeInitialValue) {
        typeSelect.value = 'custom';
        typeCustom.value = typeInitialValue;
        syncType();
    }

    syncBrand();
    syncType();

    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function () {
            syncBrand();
            syncType();
        });
    }

    function updateShoeSizeVisibility() {
        const typeValue = typeFinal.value.toLowerCase().trim();
        const shoeBlock = document.getElementById('shoe_sizes_block');
        console.log('Тип товара:', typeFinal.value);

        if (typeValue === 'взуття') {
            shoeBlock.style.display = 'block';
        } else {
            shoeBlock.style.display = 'none';
        }
    }
});