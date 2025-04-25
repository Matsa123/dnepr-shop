
window.previewMainImage = function (event) {
    const input = event.target;
    const preview = document.getElementById('main-image-preview');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
        preview.src = '';
    }
}
window.previewImages = function (event) {
    const input = event.target;
    const files = input.files;

    const container = document.querySelector('.image-thumbnails');
    container.innerHTML = ''; // очищаем старые превью

    Array.from(files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function (e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'thumbnail';

            const wrapper = document.createElement('div');
            wrapper.className = 'thumbnail-wrapper';
            wrapper.appendChild(img);

            container.appendChild(wrapper);
        };
        reader.readAsDataURL(file);
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const brandSelect = document.getElementById('brand_select');
    const brandCustom = document.getElementById('brand_custom');
    const brandFinal = document.getElementById('brand_final');

    const colorSelect = document.getElementById('color_select');
    const colorCustom = document.getElementById('color_custom');

    const typeSelect = document.getElementById('type_select');
    const typeCustom = document.getElementById('type_custom');
    const typeFinal = document.getElementById('type_final');

    function syncBrand() {
        if (brandSelect.value === 'custom') {
            brandCustom.style.display = 'block';
            brandFinal.value = brandCustom.value;
        } else {
            brandCustom.style.display = 'none';
            brandFinal.value = brandSelect.value;
        }
    }

    function toggleCustomColor() {
        const colorSelect = document.getElementById('color_select');
        const colorCustom = document.getElementById('color_custom');
        colorCustom.style.display = colorSelect.value === 'custom' ? 'block' : 'none';
    }

    function syncType() {
        if (typeSelect.value === 'custom') {
            typeCustom.style.display = 'block';
            typeFinal.value = typeCustom.value;
        } else {
            typeCustom.style.display = 'none';
            typeFinal.value = typeSelect.value;
        }
    }

    // Обработчики
    brandSelect.addEventListener('change', syncBrand);
    brandCustom.addEventListener('input', function () {
        if (brandSelect.value === 'custom') {
            brandFinal.value = brandCustom.value;
        }
    });

    colorSelect.addEventListener('change', toggleCustomColor);

    typeSelect.addEventListener('change', syncType);
    typeCustom.addEventListener('input', function () {
        if (typeSelect.value === 'custom') {
            typeFinal.value = typeCustom.value;
        }
    });


    // Инициализация
    syncBrand();
    toggleCustomColor();
    syncType();
});