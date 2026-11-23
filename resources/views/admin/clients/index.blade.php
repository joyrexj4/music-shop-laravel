@extends('layouts.main')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('title', 'Управление клиентами')

@section('content')
<div class="admin-container">
    <div class="header-container">
        <h1>Управление клиентами</h1>
        <div class="controls-container">
            <a href="{{ route('admin.clients.index') }}" class="btn-filter">Все</a>
            <a href="{{ route('admin.clients.active') }}" class="btn-filter">Активные</a>
            <a href="{{ route('admin.clients.blocked') }}" class="btn-filter btn-filter-blocked">Заблокированные</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="search-bar">
        <form method="GET" action="{{ route('admin.clients.search') }}">
            <input type="text" name="search" placeholder="Поиск по фамилии, имени, email, логину или телефону..." value="{{ request('search') }}">
            <button class="btn-find" type="submit">Найти</button>
            @if(request('search'))
                <a href="{{ route('admin.clients.index') }}" class="btn-reset">Сбросить</a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ФИО</th>
                    <th>Логин</th>
                    <th>Email</th>
                    <th>Телефон</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                <tr>
                    <td>{{ $client->Id }}</td>
                    <td>
                        {{ $client->Surname }}<br>
                        {{ $client->Name }} {{ $client->Patronymic }}
                    </td>
                    <td>{{ $client->login }}</td>
                    <td>{{ $client->email }}</td>
                    <td>{{ $client->Phone ?? 'Не указан' }}</td>
                    <td>
                        @if($client->IsBlocked)
                            <span>Заблокирован</span>
                        @else
                            <span>Активен</span>
                        @endif
                    </td>
                    <td class="actions-cell">
                        <div class="actions">
                            @if($client->IsBlocked)
                                <form action="{{ route('admin.clients.unban', $client->Id) }}" method="POST" class="unban-form">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn-unban" onclick="return confirm('Разблокировать клиента?')">Разблокировать</button>
                                </form>
                            @else
                                <form action="{{ route('admin.clients.ban', $client->Id) }}" method="POST" class="ban-form">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn-ban" onclick="return confirm('Заблокировать клиента')">Заблокировать</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <td>
                    <td colspan="7" class="text-center">Клиенты не найдены</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $clients->links() }}
    </div>
</div>
@endsection