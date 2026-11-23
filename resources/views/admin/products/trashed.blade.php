@extends('layouts.main')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('title', 'Корзина товаров')

@section('content')
<div class="admin-container">
    <div class="header-container">
        <h1>Корзина товаров</h1>
        <div>
            <a href="{{ route('admin.products.index') }}" class="btn-back">← Активные товары</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Изображение</th>
                    <th>Название</th>
                    <th>Артикул</th>
                    <th>Бренд</th>
                    <th>Категория</th>
                    <th>Цена</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>{{ $product->Id }}</td>
                    <td>
                        <img src="{{ asset($product->Image_Path) }}" alt="{{ $product->Name }}" class="product-thumb">
                    </td>
                    <td>{{ $product->Name }}</td>
                    <td>{{ $product->Article }}</td>
                    <td>{{ $product->brand->Name ?? 'Нет' }}</td>
                    <td>{{ $product->category->Name ?? 'Нет' }}</td>
                    <td>{{ number_format($product->Price, 0, ',', ' ') }} ₽</td>
                    <td class="actions-cell">
                        <div class="actions">
                            <form action="{{ route('admin.products.restore', $product->Id) }}" method="POST" class="restore-form">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn-restore">Восстановить</button>
                            </form>
                            <form action="{{ route('admin.products.force-delete', $product->Id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-force-delete" onclick="return confirm('Вы уверены, что хотите окончательно удалить этот товар? Это действие нельзя отменить!')">Удалить навсегда</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Корзина пуста</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $products->links() }}
    </div>
</div>
@endsection