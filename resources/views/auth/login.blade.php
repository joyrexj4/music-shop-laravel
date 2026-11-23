@extends('layouts.main')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('title', 'Вход - Музыкальный магазин')

@section('content')
<div class="container">
    <div class="header-container">
        <h1>Вход в систему</h1>
    </div>
        @error('login')
            <div class="error">{{ $message }}</div>
        @enderror
        @error('password')
            <div class="error">{{ $message }}</div>
        @enderror
    <div class="login-form">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <label for="login">Email или Логин:</label>
            <input type="text" name="login" id="login" value="{{ old('login') }}" required placeholder="Введите email или логин">

            <label for="password">Пароль:</label>
            <input type="password" name="password" id="password" required>

            <a href="{{ route('register') }}">Зарегистрироваться</a>
            
            <button type="submit" class="login">Войти</button>
        </form>
    </div>
</div>
@endsection