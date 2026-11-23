@extends('layouts.main')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('title', 'Управление сотрудниками')

@section('content')
<div class="admin-container">
    <div class="header-container">
        <h1>Управление сотрудниками</h1>
        <div class="controls-container">
            <a href="{{ route('admin.employees.index') }}" class="btn-filter">Все</a>
            <a href="{{ route('admin.employees.active') }}" class="btn-filter">Активные</a>
            <a href="{{ route('admin.employees.blocked') }}" class="btn-filter btn-filter-blocked">Заблокированные</a>
            <a href="{{ route('admin.employees.create') }}" class="btn-add">Добавить сотрудника</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    <div class="search-bar">
        <form method="GET" action="{{ route('admin.employees.search') }}">
            <input type="text" name="search" placeholder="Поиск по фамилии, имени, email или логину..." value="{{ request('search') }}">
            <button class="btn-find" type="submit">Найти</button>
            @if(request('search'))
                <a href="{{ route('admin.employees.index') }}" class="btn-reset">Сбросить</a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ФИО</th>
                    <th>Email</th>
                    <th>Логин</th>
                    <th>Должность</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $employee)
                <tr>
                    <td>{{ $employee->Id }}</td>
                    <td>
                        {{ $employee->Surname }}<br>
                        {{ $employee->Name }} {{ $employee->Patronymic }}
                    </td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->login }}</td>
                    <td>
                        <span class="job-badge">{{ $employee->job->Name ?? 'Не назначена' }}</span>
                    </td>
                    <td>
                        @if($employee->IsBlocked)
                            <span class="status-badge status-blocked">Заблокирован</span>
                        @else
                            <span class="status-badge status-active">Активен</span>
                        @endif
                    </td>
                    <td class="actions-cell">
                        <div class="actions">
                            <a href="{{ route('admin.employees.edit', $employee->Id) }}" class="btn-edit">Редактировать</a>
                            
                            @if(auth()->guard('employee')->id() != $employee->Id)
                                @if($employee->IsBlocked)
                                    <form action="{{ route('admin.employees.unban', $employee->Id) }}" method="POST" class="unban-form">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn-unban" onclick="return confirm('Разблокировать сотрудника?')">Разблокировать</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.employees.ban', $employee->Id) }}" method="POST" class="ban-form">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn-ban" onclick="return confirm('Заблокировать сотрудника? Он не сможет войти в систему.')">Заблокировать</button>
                                    </form>
                                @endif
                                
                                <form action="{{ route('admin.employees.destroy', $employee->Id) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" onclick="return confirm('Вы уверены, что хотите удалить этого сотрудника?')">Удалить</button>
                                </form>
                            @else
                                <span class="current-user-badge">Вы (текущий)</span>
                            @endif
                        </div>
                    </td>
                    <td class="actions-cell">
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Сотрудники не найдены</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $employees->links() }}
    </div>
</div>
@endsection