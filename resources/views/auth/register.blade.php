@extends('layouts.main')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/registration.css') }}">
@endsection

@section('title', 'Регистрация - Музыкальный магазин')

@section('content')
<div class="container">
    <div class="header-container">
        <h1>Регистрация</h1>
    </div>
    @error('lastname')
        <div class="error">{{ $message }}</div>
    @enderror

    @error('firstname')
        <div class="error">{{ $message }}</div>
    @enderror

    @error('email')
        <div class="error">{{ $message }}</div>
    @enderror

    @error('username')
        <div class="error">{{ $message }}</div>
    @enderror

    @error('password')
        <div class="error">{{ $message }}</div>
    @enderror

    @error('agreement')
        <div class="error">{{ $message }}</div>
    @enderror
    <div class="registration-form">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <label for="lastname">Фамилия:</label>
            <input type="text" name="lastname" id="lastname" value="{{ old('lastname') }}" required>

            <label for="firstname">Имя:</label>
            <input type="text" name="firstname" id="firstname" value="{{ old('firstname') }}" required>

            <label for="patronymic">Отчество:</label>
            <input type="text" name="patronymic" id="patronymic" value="{{ old('patronymic') }}">

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>

            <label for="username">Логин:</label>
            <input type="text" name="username" id="username" value="{{ old('username') }}" required>
            
            <label for="password">Пароль:</label>
            <input type="password" name="password" id="password" required>


            <label for="password_confirmation">Подтверждение пароля:</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>

            <div class="agreement">
                <input type="checkbox" name="agreement" id="agreement" required>
                <label for="agreement">Согласен с условиями сайта</label>
            </div>

            <a href="{{ route('login') }}">Есть аккаунт. Войти</a>
    
            <button type="submit" class="register">Зарегистрироваться</button>
        </form>
    </div>
</div>
@endsection