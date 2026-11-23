@extends('layouts.main')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
@endsection

@section('title', 'Поиск товара — Музыкальный магазин')

@section('content')
    <div class="header-container">
        <h1>Поиск товара</h1>
    </div>

    <form method="GET" action="{{ route('search') }}" class="filter-form">
        <div class="filter-container">
            <p>Категория товара:</p>
            <select name="category">
                <option value="0">-</option>
                @foreach($categories as $category)
                    <option value="{{ $category->Id }}" 
                        {{ $selected_category == $category->Id ? 'selected' : '' }}>
                        {{ $category->Name }}
                    </option>
                @endforeach
            </select>

            <p>Бренд:</p>
            <select name="brand">
                <option value="0">-</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->Id }}" 
                        {{ $selected_brand == $brand->Id ? 'selected' : '' }}>
                        {{ $brand->Name }}
                    </option>
                @endforeach
            </select>

            <p>Ценовой диапазон</p>
            <p>От:</p>
            <input type="number" name="range_from" value="{{ $price_from }}" placeholder="0">
            <p>До:</p>
            <input type="number" name="range_to" value="{{ $price_to }}" placeholder="1000000">

            <div class="filter-buttons">
                <button type="submit">Применить фильтр</button>
                <button type="reset" onclick="window.location.href='{{ route('search') }}'">Сбросить фильтр</button>
            </div>
        </div>
    </form>

    <div class="products-wrapper">
        <div class="product-grid">
            @if(count($products) > 0)
                @foreach($products as $product)
                <div class="product-card" data-product-id="{{ $product->Id }}">
                    <div class="product-card-content">
                        <a href="{{ route('product.show', $product->Id) }}" class="product-link">
                            <div class="product-image-background">
                                <img src="{{ asset($product->Image_Path) }}" class="product-image" alt="{{ $product->Name }}">
                            </div>
                            <h3 class="product-title">{{ $product->Name }}</h3>
                            <p class="product-price">{{ number_format($product->Price, 0, ',', ' ') }} ₽</p>
                        </a>
                        <button class="product-button" onclick="event.stopPropagation(); addToCart({{ $product->Id }})">
                            В КОРЗИНУ
                        </button>
                    </div>
                </div>
                @endforeach
            @else
                <div class="no-results">
                    <p>Товары не найдены</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        function addToCart(productId) {
            fetch('{{  route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                } else if (data.error) {
                    if (data.error === 'Пользователь не аутентифицирован') {
                        alert('Войдите в систему, чтобы добавить товары в корзину');

                        window.location.href = '{{ route("login") }}';
                    } else {
                        alert('Ошибка' + data.error);
                    }
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
                alert('Ошибка при добавлении товара');
            })
        }
    </script>
@endsection