@extends('layouts.main')


@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('title', 'Админ панель')

@section('content')
<div class="container">
    <h1>Панель сотрудника</h1>
    
    <div class="admin-menu">
        <ul>
            @can('manage-products')
                <li><a href="{{ route('admin.products.index') }}">Управление товарами</a></li>
            @endcan
            
            @can('manage-orders')
                <li><a href="{{ route('admin.orders.index') }}">Управление заказами</a></li>
            @endcan
            
            @can('manage-clients')
                <li><a href="{{ route('admin.clients.index') }}">Управление клиентами</a></li>
            @endcan
            
            @can('manage-employees')
                <li><a href="{{ route('admin.employees.index') }}">Управление сотрудниками</a></li>
            @endcan
        </ul>
    </div>
</div>
@endsection