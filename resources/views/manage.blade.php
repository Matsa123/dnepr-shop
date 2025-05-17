@extends('app')

@section('content')
    <div class="manage-container">
        <!-- Боковая панель со списком товаров -->
        <aside class="sidebar">
            <h3>Все товары</h3>
            <ul class="product-list">
                @foreach($allProducts as $p)
                    <li class="{{ isset($product) && $product->id === $p->id ? 'active' : '' }}">
                        <a href="{{ route('manage', ['id' => $p->id]) }}">{{ $p->name }}</a>
                    </li>
                @endforeach
                <li>
                    <a href="{{ route('manage') }}" class="new-product-button">+ Новый товар</a>
                </li>
            </ul>
        </aside>

        <!-- Основная форма редактирования товара -->
        <div class="redact_all">
            <div class="product-form-wrapper">
                <h2 class="product-form-title">
                    {{ isset($product) ? 'Редактировать товар' : 'Добавить товар' }}
                </h2>

                <form action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}"
                    method="POST" enctype="multipart/form-data" class="product-form">
                    @csrf
                    @if(isset($product))
                        @method('PUT')
                    @endif

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

                    <div id="shoe_sizes_block" style="display: none; margin-top: 10px;">
                        <label>Розміри взуття:</label>
                        <div>
                            @for ($i = 36; $i <= 46; $i++)
                                <label style="margin-right: 10px;">
                                    <input type="checkbox" name="shoe_sizes[]" value="{{ $i }}" {{ (is_array(old('shoe_sizes', $product->shoe_sizes ?? [])) && in_array($i, old('shoe_sizes', $product->shoe_sizes ?? []))) ? 'checked' : '' }}>
                                    {{ $i }}
                                </label>
                            @endfor
                        </div>
                    </div>
                    <label>
                        Пол:
                        <select name="gender">
                            <option value="">Выберите</option>
                            <option value="Чоловіча" {{ old('gender', $product->gender ?? '') === 'Чоловіча' ? 'selected' : '' }}>
                                Чоловіча</option>
                            <option value="Жіноча" {{ old('gender', $product->gender ?? '') === 'Жіноча' ? 'selected' : '' }}>
                                Жіноча</option>
                            <option value="Дитяча" {{ old('gender', $product->gender ?? '') === 'Дитяча' ? 'selected' : '' }}>
                                Дитяча</option>
                        </select>
                    </label>

                    <label>
                        Розміри одягу:
                        <select name="clothing_sizes[]" multiple>
                            @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
                                <option value="{{ $size }}" @if(isset($product) && in_array($size, $product->clothing_sizes ?? [])) selected @endif>
                                    {{ $size }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <label>
                        Цвет:
                        <select name="color" id="color_select" onchange="toggleCustomColor()">
                            <option value="">Выберите цвет</option>
                            @foreach($colors as $color)
                                <option value="{{ $color }}" {{ old('color', $product->color ?? '') === $color ? 'selected' : '' }}>{{ $color }}
                                </option>
                            @endforeach
                            <option value="custom" {{ old('color', $product->color ?? '') === 'custom' ? 'selected' : '' }}>+
                                Новый цвет</option>
                            <style>
                                #color_custom {
                                    display:
                                        {{ old('color', $product->color ?? '') === 'custom' ? 'block' : 'none' }}
                                    ;
                                }
                            </style>
                        </select>

                        <input type="text" name="color_custom" id="color_custom" placeholder="Введите цвет"
                            value="{{ old('color_custom', (old('color') === 'custom' ? $product->color ?? '' : '')) }}">
                    </label>

                    <label>
                        Цена:
                        <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}" required>
                    </label>

                    <label>
                        Главная фотография:
                        <label class="custom-file-label">
                            Изменить главное фото
                            <input type="file" name="image" class="custom-file-input" onchange="previewMainImage(event)">
                        </label>
                        <img id="main-image-preview" class="preview-image"
                            style="display:none; max-width: 150px; margin-top: 10px;" />

                        @if(isset($product) && $product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="Главное фото" class="preview-image">
                        @endif
                    </label>

                    <div class="form-group input_marg">
                        <label for="images">Дополнительные изображения:</label>
                        <label class="custom-file-label">
                            Добавить еще фото
                            <input type="file" id="images" class="custom-file-input" name="images[]" multiple
                                onchange="previewImages(event)">
                        </label>

                        <div class="image-thumbnails" style="display: flex; flex-wrap: wrap; gap: 10px;"></div>
                    </div>

                    <button type="submit" class="btn-submit">
                        {{ isset($product) ? 'Сохранить изменения' : 'Добавить товар' }}
                    </button>

                </form>

                @if(isset($product) && $product->image)
                    <div style="margin-top: 1rem;">
                        <form action="{{ route('product_main_image.delete', $product->id) }}" method="POST"
                            onsubmit="return confirm('Удалить главное изображение?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete-image">Удалить главное изображение</button>
                        </form>
                    </div>
                @endif
                {{-- Показываем уже загруженные изображения и даём возможность удалить --}}
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
                @if(isset($product))
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="delete-product-form"
                        onsubmit="return confirm('Удалить этот товар?');" style="margin-top: 1rem;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete">Удалить товар</button>
                    </form>
                @endif
            </div>

            <form method="POST" class="logout-form" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-button">Выйти из редактирования</button>
            </form>
        </div>
    </div>
@endsection

@vite(['resources/css/manage.css', 'resources/js/brand.js'])