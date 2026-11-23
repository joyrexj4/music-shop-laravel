@extends('layouts.main')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('title', 'Добавить сотрудника')

@section('content')
<div class="admin-container">
    <div class="header-container">
        <h1>Добавить сотрудника</h1>
        <a href="{{ route('admin.employees.index') }}" class="btn-back">Назад</a>
    </div>

    @if($errors->any())
        <div class="alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.employees.store') }}" class="product-form">
        @csrf

        <div class="form-grid">
            <div class="form-group">
                <label for="surname">Фамилия *</label>
                <input type="text" name="surname" id="surname" value="{{ old('surname') }}" required maxlength="30">
            </div>

            <div class="form-group">
                <label for="name">Имя *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required maxlength="30">
            </div>

            <div class="form-group">
                <label for="patronymic">Отчество</label>
                <input type="text" name="patronymic" id="patronymic" value="{{ old('patronymic') }}" maxlength="30">
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required maxlength="50">
            </div>

            <div class="form-group">
                <label for="login">Логин *</label>
                <input type="text" name="login" id="login" value="{{ old('login') }}" required maxlength="30">
            </div>

            <div class="form-group">
                <label for="password">Пароль *</label>
                <input type="password" name="password" id="password" required minlength="6">
                <small class="form-text">Минимум 6 символов</small>
            </div>

            <div class="form-group">
                <label for="job_id">Должность *</label>
                <select name="job_id" id="job_id" required>
                    <option value="">Выберите должность</option>
                    @foreach($jobTitles as $job)
                        <option value="{{ $job->Id }}" {{ old('job_id') == $job->Id ? 'selected' : '' }}>
                            {{ $job->Name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Добавить сотрудника</button>
            <a href="{{ route('admin.employees.index') }}" class="btn-cancel">Отмена</a>
        </div>
    </form>
</div>
@endsection