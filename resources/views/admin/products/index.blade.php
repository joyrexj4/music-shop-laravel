@extends('layouts.main')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('title', 'Управление товарами')

@section('content')
<div class="admin-container">
    <div class="header-container">
        <h1>Управление товарами</h1>
        <div class="controls-container">
            <a href="{{ route('admin.products.create') }}" class="btn-add">Добавить товар</a>
            <a href="{{ route('admin.products.trashed') }}" class="btn-trash">Удалённые товары</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="search-bar">
        <form method="GET" action="{{ route('admin.products.search') }}">
            <input type="text" name="search" placeholder="Поиск по названию/артиклю" value="{{ request('search') }}">
            <button class="btn-find" type="submit">Найти</button>
            @if(request('search'))
                <a href="{{ route('admin.products.index') }}" class="btn-reset">Сбросить</a>
            @endif
        </form>
    </div>

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
                    <th>Количество</th>
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
                    <td>
                        <span>
                            {{ $product->Amount }}
                        </span>
                    </td>
                    <td class="actions-cell">
                        <div class="actions">
                            <a href="{{ route('admin.products.edit', $product->Id) }}" class="btn-edit">Редактировать</a>
                            <form action="{{ route('admin.products.destroy', $product->Id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('Вы уверены, что хотите удалить этот товар?')">Удалить</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">Товары не найдены</td>
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