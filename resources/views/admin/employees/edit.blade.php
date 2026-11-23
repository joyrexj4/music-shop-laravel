@extends('layouts.main')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('title', 'Редактировать сотрудника')

@section('content')
<div class="admin-container">
    <div class="header-container">
        <h1>Редактировать сотрудника</h1>
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

    <form method="POST" action="{{ route('admin.employees.update', $employee->Id) }}" class="product-form">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="form-group">
                <label for="surname">Фамилия *</label>
                <input type="text" name="surname" id="surname" value="{{ old('surname', $employee->Surname) }}" required maxlength="30">
            </div>

            <div class="form-group">
                <label for="name">Имя *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $employee->Name) }}" required maxlength="30">
            </div>

            <div class="form-group">
                <label for="patronymic">Отчество</label>
                <input type="text" name="patronymic" id="patronymic" value="{{ old('patronymic', $employee->Patronymic) }}" maxlength="30">
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" name="email" id="email" value="{{ old('email', $employee->email) }}" required maxlength="50">
            </div>

            <div class="form-group">
                <label for="login">Логин *</label>
                <input type="text" name="login" id="login" value="{{ old('login', $employee->login) }}" required maxlength="30">
            </div>

            <div class="form-group">
                <label for="password">Новый пароль</label>
                <input type="password" name="password" id="password" minlength="6">
                <small class="form-text">Оставьте пустым, чтобы не менять пароль</small>
            </div>

            <div class="form-group">
                <label for="job_id">Должность *</label>
                <select name="job_id" id="job_id" required>
                    <option value="">Выберите должность</option>
                    @foreach($jobTitles as $job)
                        <option value="{{ $job->Id }}" {{ old('job_id', $employee->Job_Id) == $job->Id ? 'selected' : '' }}>
                            {{ $job->Name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Сохранить изменения</button>
            <a href="{{ route('admin.employees.index') }}" class="btn-cancel">Отмена</a>
        </div>
    </form>
</div>
@endsection