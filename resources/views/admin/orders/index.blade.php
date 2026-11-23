@extends('layouts.main')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('title', 'Управление заказами')

@section('content')
<div class="admin-container">
    <div class="header-container">
        <h1>Управление заказами</h1>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="search-bar">
        <form method="GET" action="{{ route('admin.orders.search') }}">
            <input type="text" name="search" placeholder="Поиск по ID заказа, клиенту или адресу..." value="{{ request('search') }}">
            <button class="btn-find" type="submit">Найти</button>
            @if(request('search'))
                <a href="{{ route('admin.orders.index') }}" class="btn-reset">Сбросить</a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID заказа</th>
                    <th>Клиент</th>
                    <th>Адрес</th>
                    <th>Статус</th>
                    <th>Сотрудник</th>
                    <th>Доставщик</th>
                    <th>Оплата</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>{{ $order->Id }}</td>
                    <td>
                        {{ $order->client->Surname ?? 'Нет' }} 
                        {{ $order->client->Name ?? '' }}
                    </td>
                    <td>{{ Str::limit($order->Client_Address, 30) }}</td>
                    <td>
                        {{ $order->orderStatus->Name ?? 'Не указан' }}
                    </td>
                    <td>
                        {{ $order->employee->Surname ?? 'Не назначен' }}
                        {{ $order->employee->Name ?? '' }}
                    </td>
                    <td>
                        {{ $order->carrier->Surname ?? 'Не назначен' }}
                        {{ $order->carrier->Name ?? '' }}
                    </td>
                    <td>{{ $order->paymentMethod->Name ?? 'Не указан' }}</td>
                    <td class="actions-cell">
                        <div class="actions">
                            <a href="{{ route('admin.orders.edit', $order->Id) }}" class="btn-edit">Редактировать</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Заказы не найдены</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $orders->links() }}
    </div>
</div>
@endsection