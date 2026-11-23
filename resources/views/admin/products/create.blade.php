@extends('layouts.main')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('title', 'Добавить товар')

@section('content')
<div class="admin-container">
    <div class="header-container">
        <h1>Добавить товар</h1>
        <a href="{{ route('admin.products.index') }}" class="btn-back">← Назад</a>
    </div>

    @if($errors->any())
        <div class="alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="product-form">
        @csrf

        <div class="form-grid">
            <div class="form-group">
                <label for="name">Название товара *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required maxlength="50">
            </div>

            <div class="form-group">
                <label for="article">Артикул *</label>
                <input type="number" name="article" id="article" value="{{ old('article') }}" required>
            </div>

            <div class="form-group">
                <label for="brand_id">Бренд *</label>
                <select name="brand_id" id="brand_id" required>
                    <option value="">Выберите бренд</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->Id }}" {{ old('brand_id') == $brand->Id ? 'selected' : '' }}>
                            {{ $brand->Name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="category_id">Категория *</label>
                <select name="category_id" id="category_id" required>
                    <option value="">Выберите категорию</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->Id }}" {{ old('category_id') == $category->Id ? 'selected' : '' }}>
                            {{ $category->Name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="price">Цена (₽) *</label>
                <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0" required>
            </div>

            <div class="form-group">
                <label for="amount">Количество *</label>
                <input type="number" name="amount" id="amount" value="{{ old('amount') }}" min="0" required>
            </div>

            <div class="form-group full-width">
                <label for="details">Краткое описание</label>
                <input type="text" name="details" id="details" value="{{ old('details') }}" maxlength="200">
            </div>

            <div class="form-group full-width">
                <label for="description">Полное описание</label>
                <textarea name="description" id="description" rows="5" maxlength="500">{{ old('description') }}</textarea>
            </div>

            <div class="form-group full-width">
                <label for="image">Изображение</label>
                <input type="file" name="image" id="image" accept="image/*">
                <small class="form-text">Поддерживаемые форматы: JPEG, PNG, JPG, GIF. Максимальный размер: 2MB</small>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Сохранить товар</button>
            <a href="{{ route('admin.products.index') }}" class="btn-cancel">Отмена</a>
        </div>
    </form>
</div>
@endsection