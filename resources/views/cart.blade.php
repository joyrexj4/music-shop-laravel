@extends('layouts.main')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
@endsection

@section('title', 'Корзина - Музыкальный магазин')

@section('content')
<div class="container">
    <div class="header-container">
        <h1>КОРЗИНА</h1>
    </div>

    
    @if(session('success'))
        <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin: 20px auto; width: fit-content;">
            {{ session('success') }}
        </div>
        
        <script>
            alert('{{ session('success') }}');
        </script>
    @endif

    @if(session('error'))
        <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin: 20px auto; width: fit-content;">
            {{ session('error') }}
        </div>
        
        <script>
            alert('{{ session('error') }}');
        </script>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin: 20px auto; width: fit-content;">
            {{ session('error') }}
        </div>
    @endif

    @error('address')
        <div class="error-message">{{ $message }}</div>
    @enderror

    @error('phone')
        <div class="error-message">{{ $message }}</div>
    @enderror

    @error('payment_method')
        <div class="error-message">{{ $message }}</div>
    @enderror
    
    @if(empty($cartContents) || count($cartContents) == 0)
        <div class="no-results">
            <p>Ваша корзина пуста</p>
            <a href="{{ route('home') }}" class="product-button" style="display: inline-block; text-decoration: none; margin-top: 20px;">Продолжить покупки</a>
        </div>
    @else
        <div class="cart-layout">
            <!-- Cart Items Section -->
            <div class="cart-items-section">
                <div class="products-wrapper">
                    <div class="product-grid">
                        @foreach($cartContents as $item)
                        <div class="product-card" data-product-id="{{ $item->product->Id }}">
                            <div class="product-card-content">
                                <a href="{{ route('product.show', $item->product->Id) }}" class="product-link">
                                    <div class="product-image-background">
                                        <img src="{{ asset($item->product->Image_Path) }}" class="product-image" alt="{{ $item->product->Name }}">
                                    </div>
                                    <h3 class="product-title">{{ $item->product->Name }}</h3>
                                    <p class="product-price">{{ number_format($item->product->Price, 0, ',', ' ') }} ₽</p>
                                </a>
                                
                                <div class="cart-item-controls">
                                    <div class="quantity-controls">
                                        <button class="quantity-btn" onclick="updateQuantity({{ $item->product->Id }}, -1)">-</button>
                                        <input type="number" class="quantity-input" 
                                               value="{{ $item->Amount }}" 
                                               id="quantity-{{ $item->product->Id }}"
                                               min="1" 
                                               onchange="setQuantity({{ $item->product->Id }}, this.value)">
                                        <button class="quantity-btn" onclick="updateQuantity({{ $item->product->Id }}, 1)">+</button>
                                    </div>
                                    <button class="product-button remove-btn" onclick="removeFromCart({{ $item->product->Id }})">
                                        УДАЛИТЬ
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Order Form Section -->
            <div class="order-form-section">
                <div class="order-summary">
                    <h2>ИТОГО: <span id="total-price">{{ number_format($total, 0, ',', ' ') }}</span> ₽</h2>
                </div>
                
                <form method="POST" action="{{ route('cart.checkout') }}" class="checkout-form">
                    @csrf
                    
                    <div class="form-group">
                        <label for="address">Адрес доставки:</label>
                        <textarea name="address" id="address" rows="3" required placeholder="Введите адрес"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Телефон:</label>
                        <input type="tel" name="phone" id="phone" required placeholder="+7XXXXXXXXXX" 
                               value="{{ Auth::guard('client')->user()->Phone ?? '' }}">
                    </div>
                    
                    <div class="form-group">
                        <label>Способ оплаты:</label>
                        <div class="payment-methods">
                            @php
                                $paymentMethods = App\Models\PaymentMethod::all();
                            @endphp
                            @foreach($paymentMethods as $method)
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="{{ $method->Id }}" required>
                                <span>{{ $method->Name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    
                    <button type="submit" class="checkout-btn">Оформить заказ</button>
                </form>
            </div>
        </div>
    @endif
</div>

<script>
    function updateQuantity(productId, change) {
        let input = document.getElementById('quantity-' + productId);
        let newQuantity = parseInt(input.value) + change;
        
        if (newQuantity < 1) {
            newQuantity = 1;
        }
        
        setQuantity(productId, newQuantity);
    }
    
    function setQuantity(productId, quantity) {
        fetch('{{ route("cart.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ошибка при обновлении количества');
        });
    }
    
    function removeFromCart(productId) {
        if (confirm('Удалить товар из корзины?')) {
            fetch('{{ route("cart.remove") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ошибка при удалении товара');
            });
        }
    }
</script>

@endsection