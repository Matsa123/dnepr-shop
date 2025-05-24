@extends('app')

@section('content')
    <div class="manage-container">
        <!-- Боковая панель со списком товаров -->
        <aside class="sidebar">
            <h3>Все товары</h3>
            <div class="new_product_btn">
                <a href="{{ route('manage') }}" class="new-product-button">+ Новый товар</a>
            </div>

            @foreach($productsByType as $type => $products)
                <h4 class="product-type-title">{{ $type ?: 'Без типа' }}</h4>
                <ul class="product-list">
                    @foreach($products as $p)
                        <li class="{{ isset($product) && $product->id === $p->id ? 'active' : '' }}">
                            <a href="{{ route('manage', ['id' => $p->id]) }}">{{ $p->name }}</a>
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </aside>
        <!-- Основная форма редактирования товара -->
        <div class="redact_all">
            <div class="product-form-wrapper">
                <h2 class="product-form-title">
                    {{ isset($product) ? 'Редактировать товар' : 'Добавить товар' }}
                </h2>
                {{-- Показываем уже загруженные изображения и даём возможность удалить --}}
                @if(isset($product) && $product->image)
                    <div class="main-image-wrapper">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="Главное фото" class="preview-image">
                        <form action="{{ route('product_main_image.delete', $product->id) }}" method="POST"
                            onsubmit="return confirm('Удалить главное изображение?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete-image">Удалить главное изображение</button>
                        </form>
                    </div>
                @endif
                @if(isset($product))
                    <div class="image-thumbnails" style="margin-top: 1rem;">
                        @foreach($product->images as $img)
                            <div class="thumbnail-wrapper">
                                <img src="{{ asset('storage/' . $img->image) }}" alt="image" class="thumbnail">
                                <form action="{{ route('product_images.destroy', $img->id) }}" method="POST"
                                    onsubmit="return confirm('Удалить это изображение?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete-image">Удалить доп. фото</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}"
                    method="POST" enctype="multipart/form-data" class="product-form">
                    @csrf
                    @if(isset($product))
                        @method('PUT')
                    @endif
                    <div class="manage_photo_btn">
                        <!-- Главное фото -->
                        <label class="custom-file-label">
                            {{ isset($product) && $product->image ? 'Изменить главную фотографию' : 'Добавить главную фотографию' }}
                            <input type="file" name="image" class="custom-file-input" onchange="uploadMainImage(event)">
                        </label>

                        @unless(isset($product) && $product->image)
                            <!-- Превью главного фото (только если товар не существует) -->
                            <div id="main-image-preview" class="image-preview"></div>
                        @endunless

                        <!-- Дополнительные фото -->
                        <div class="form-group input_marg">
                            <label class="custom-file-label" for="images">
                                Добавить еще фото
                            </label>
                            <input type="file" id="images" class="custom-file-input" name="images[]" multiple
                                onchange="previewImages(event)">
                        </div>

                        @unless(isset($product))
                            <!-- Превью дополнительных фото (только если товар не существует) -->
                            <div id="additional-images-preview" class="image-preview"></div>
                        @endunless
                    </div>
                    <input type="hidden" id="additional_images_data" name="additional_images_data" value="[]">
                    <label>
                        Название:
                        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required>
                    </label>

                    <label>
                        Описание:
                        <textarea name="description">{{ old('description', $product->description ?? '') }}</textarea>
                    </label>

                    <label>
                        Бренд:
                        <select name="brand_select" id="brand_select" onchange="syncBrand()">
                            <option value="">Выберите бренд</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand }}" {{ (old('brand', $product->brand ?? '') === $brand) ? 'selected' : '' }}>{{ $brand }}
                                </option>
                            @endforeach
                            <option value="custom">+ Новый бренд</option>
                        </select>

                        <input type="text" name="brand_custom" id="brand_custom" placeholder="Введите бренд"
                            value="{{ old('brand', $product->brand ?? '') }}" style="display: none;" oninput="syncBrand()">

                        <!-- Скрытое поле, которое будет отправляться -->
                        <input type="hidden" name="brand" id="brand_final"
                            value="{{ old('brand', $product->brand ?? '') }}">
                    </label>
                    <label>
                        Тип товару:
                        <select name="type_select" id="type_select">
                            <option value="">Оберіть тип</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}" {{ old('type', $product->type ?? '') === $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                            <option value="custom">+ Новий тип</option>
                        </select>

                        <input type="text" name="type_custom" id="type_custom" placeholder="Введіть тип"
                            value="{{ old('type', $product->type ?? '') }}" style="display: none;">

                        <input type="hidden" name="type" id="type_final" value="{{ old('type', $product->type ?? '') }}">
                    </label>

                    <div id="shoe_sizes_block" style="display: none;">
                        <label>Розміри взуття:</label>
                        <div>
                            @for ($i = 36; $i <= 46; $i++)
                                <label>
                                    <input type="checkbox" name="shoe_sizes[]" value="{{ $i }}" {{ (is_array(old('shoe_sizes', $product->shoe_sizes ?? [])) && in_array($i, old('shoe_sizes', $product->shoe_sizes ?? []))) ? 'checked' : '' }}>
                                    {{ $i }}
                                </label>
                            @endfor
                        </div>
                    </div>
                    {{-- Пол --}}
                    <label for="gender">Пол:</label>
                    <select name="gender" id="gender">
                        <option value="">Выберите</option>
                        <option value="Чоловіча" {{ old('gender', $product->gender ?? '') === 'Чоловіча' ? 'selected' : '' }}>
                            Чоловіча</option>
                        <option value="Жіноча" {{ old('gender', $product->gender ?? '') === 'Жіноча' ? 'selected' : '' }}>
                            Жіноча</option>
                        <option value="Дитяча" {{ old('gender', $product->gender ?? '') === 'Дитяча' ? 'selected' : '' }}>
                            Дитяча</option>
                    </select>

                    {{-- Размеры --}}
                    <p class="form-label">Розміри одягу:</p>
                    <div class="size-checkboxes">
                        @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
                            <label class="size-box">
                                <input type="checkbox" name="clothing_sizes[]" value="{{ $size }}" {{ (isset($product) && in_array($size, $product->clothing_sizes ?? [])) ? 'checked' : '' }}>
                                <span>{{ $size }}</span>
                            </label>
                        @endforeach
                    </div>

                    {{-- Цвет --}}
                    <label for="color_select">Цвет:</label>
                    <select name="color" id="color_select" onchange="toggleCustomColor()">
                        <option value="">Выберите цвет</option>
                        @foreach($colors as $color)
                            <option value="{{ $color }}" {{ old('color', $product->color ?? '') === $color ? 'selected' : '' }}>
                                {{ $color }}
                            </option>
                        @endforeach
                        <option value="custom" {{ old('color', $product->color ?? '') === 'custom' ? 'selected' : '' }}>+
                            Новый цвет</option>
                    </select>

                    <div id="color_custom_block"
                        style="display: {{ old('color', $product->color ?? '') === 'custom' ? 'block' : 'none' }};">
                        <input type="text" name="color_custom" id="color_custom" placeholder="Введите цвет"
                            value="{{ old('color_custom', (old('color') === 'custom' ? $product->color ?? '' : '')) }}">
                    </div>

                    <label>
                        Цена:
                        <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}" required>
                    </label>
                    <button type="submit" class="btn-submit">
                        {{ isset($product) ? 'Сохранить изменения' : 'Добавить товар' }}
                    </button>
                </form>
                @if(isset($product))
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="delete-product-form"
                        onsubmit="return confirm('Удалить этот товар?');" style="margin-top: 1rem;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete">Удалить товар</button>
                    </form>
                @endif
            </div>

        </div>
    </div>
    <form method="POST" class="logout-form" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-button">Выйти из редактирования</button>
    </form>
@endsection

@vite(['resources/css/manage.css', 'resources/js/brand.js', 'resources/css/manage_aside.css'])