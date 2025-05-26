
window.uploadMainImage = function (event) {
    const input = event.target;
    const file = input.files[0];
    const productIdMeta = document.querySelector('meta[name="product-id"]');
    const productId = productIdMeta ? productIdMeta.getAttribute('content') : null;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (!file) return;

    if (!productId) {
        // Новый товар — показываем превью, но не отправляем на сервер
        const reader = new FileReader();
        reader.onload = function (e) {
            let preview = document.getElementById('main-image-preview');
            if (preview) {
                preview.innerHTML = `<img src="${e.target.result}" alt="Главное фото" class="preview-image">`;
            }
        };
        reader.readAsDataURL(file);
        return;
    }

    // Существующий товар — загружаем на сервер
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
function uploadAdditionalImage(event) {
    const input = event.target;
    const files = input.files;
    const previewContainer = document.getElementById('additional-images-preview');
    const serverContainer = document.querySelector('.image-thumbnails');
    const productIdMeta = document.querySelector('meta[name="product-id"]');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const productId = productIdMeta ? productIdMeta.getAttribute('content') : null;

    if (!files.length) return;

    // Для нового товара — массив base64 изображений
    const newImagesBase64 = [];

    Array.from(files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function (e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.alt = 'Предпросмотр';
            img.className = 'thumbnail';

            if (!productId) {
                // Новый товар — просто показываем превью
                previewContainer.appendChild(img);

                // Добавляем base64 в массив
                newImagesBase64.push(e.target.result);

                // Обновляем скрытое поле с массивом base64
                const hiddenInput = document.getElementById('additional_images_data');
                if (hiddenInput) {
                    // Получаем текущие данные, если они есть
                    let currentData = hiddenInput.value ? JSON.parse(hiddenInput.value) : [];
                    currentData.push(e.target.result);
                    hiddenInput.value = JSON.stringify(currentData);
                }
            } else {
                // Существующий товар — сразу отправляем на сервер
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
                        serverContainer.appendChild(wrapper);
                    })
                    .catch(error => {
                        console.error('Ошибка при загрузке изображения:', error);
                        alert('Не удалось загрузить изображение.');
                    });
            }
        };

        reader.readAsDataURL(file);
    });

    if (productId) {
        input.value = '';
    }
}
window.previewImages = function (event) {
    uploadAdditionalImage(event);
}

document.addEventListener('DOMContentLoaded', function () {
    toggleCustomColor(); // запуск при загрузке страницы
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

// ВНЕ document.addEventListener — глобально!
window.toggleCustomColor = function () {
    const select = document.getElementById('color_select');
    const customBlock = document.getElementById('color_custom_block');
    if (select.value === 'custom') {
        customBlock.style.display = 'block';
    } else {
        customBlock.style.display = 'none';
    }
};
