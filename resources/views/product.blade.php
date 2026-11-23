@extends('layouts.main')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endsection

@section('title', $product->Name . ' - Музыкальный магазин')

@section('content')
<div class="product-wrapper">
    <div class="product-grid">
        <div class="product-image-background">
            <img src="{{ asset($product->Image_Path) }}" alt="{{ $product->Name }}" class="product-image">
        </div>
        
        <div class="product-content">
            <h1 class="product-title">{{ $product->Name }}</h1>
            <p class="product-price">{{ number_format($product->Price, 0, ',', ' ') }} ₽</p>
            <p class="product-article">Артикул: {{ $product->Article }}</p>
            <p class="product-details">{!! nl2br(e($product->Details)) !!}</p>
            <p class="product-amount">Количество на складе: {{ $product->Amount }}</p>

            <div class="product-buttons">
                <button class="product-button-cart" onclick="addToCart({{ $product->Id }})">ДОБАВИТЬ В КОРЗИНУ</button>
                <button class="product-button-buy" onclick="buyNow({{ $product->Id }})">ЗАКАЗАТЬ</button>
            </div>
        </div>
    </div>
</div>

<div class="product-description">
    <h3 class="description-title">О товаре</h3>
    <p class="description">{!! nl2br(e($product->Description)) !!}</p>
</div>

<script>
    function addToCart(productId) {
        alert('Товар ' + productId + ' добавлен в корзину');
    }
    
    function buyNow(productId) {
        alert('Оформление заказа для товара ' + productId);
    }
</script>
@endsection