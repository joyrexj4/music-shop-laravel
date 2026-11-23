@extends('layouts.main')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('title', 'Главная - Музыкальный магазин')

@section('content')
    <div class="container-header">
        <h1>МАГАЗИН МУЗЫКАЛЬНЫХ ИНСТРУМЕНТОВ</h1>
    </div>
    
    <div class="parallax"></div>

    <div class="products-header">
        <h1>ТОВАРЫ</h1>
    </div>

    <div class="products-wrapper">
        <div class="product-grid">
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
        </div>
    </div>

    <script>
        function addToCart(productId) {
            alert('Товар ' + productId + ' добавлен в корзину');
        }
    </script>
@endsection