
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