@extends('layouts.main')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('title', 'Редактировать заказ #' . $order->Id)

@section('content')
<div class="admin-container">
    <div class="header-container">
        <h1>Редактировать заказ #{{ $order->Id }}</h1>
        <a href="{{ route('admin.orders.index') }}" class="btn-back">Назад</a>
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

    <form method="POST" action="{{ route('admin.orders.update', $order->Id) }}" class="product-form">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="form-group">
                <label for="order_status_id">Статус заказа *</label>
                <select name="order_status_id" id="order_status_id" required>
                    <option value="">Выберите статус</option>
                    @foreach($orderStatuses as $status)
                        <option value="{{ $status->Id }}" {{ old('order_status_id', $order->Order_Status_Id) == $status->Id ? 'selected' : '' }}>
                            {{ $status->Name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="payment_method_id">Способ оплаты *</label>
                <select name="payment_method_id" id="payment_method_id" required>
                    <option value="">Выберите способ оплаты</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->Id }}" {{ old('payment_method_id', $order->Payment_Method_Id) == $method->Id ? 'selected' : '' }}>
                            {{ $method->Name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="employee_id">Ответственный сотрудник</label>
                <select name="employee_id" id="employee_id">
                    <option value="">Выберите сотрудника</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->Id }}" {{ old('employee_id', $order->Employee_Id) == $employee->Id ? 'selected' : '' }}>
                            {{ $employee->Surname }} {{ $employee->Name }} ({{ $employee->job->Name ?? 'Нет должности' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="carrier_id">Доставщик</label>
                <select name="carrier_id" id="carrier_id">
                    <option value="">Выберите доставщика</option>
                    @foreach($carriers as $carrier)
                        <option value="{{ $carrier->Id }}" {{ old('carrier_id', $order->Carrier_Id) == $carrier->Id ? 'selected' : '' }}>
                            {{ $carrier->Surname }} {{ $carrier->Name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group full-width">
                <label for="client_address">Адрес доставки *</label>
                <textarea name="client_address" id="client_address" rows="3" required maxlength="100">{{ old('client_address', $order->Client_Address) }}</textarea>
            </div>

            <div class="form-group full-width">
                <label>Информация о клиенте</label>
                <div class="info-box">
                    <p><strong>Имя:</strong> {{ $order->client->Surname }} {{ $order->client->Name }} {{ $order->client->Patronymic }}</p>
                    <p><strong>Email:</strong> {{ $order->client->email }}</p>
                    <p><strong>Телефон:</strong> {{ $order->client->Phone ?? 'Не указан' }}</p>
                    <p><strong>Логин:</strong> {{ $order->client->login }}</p>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Сохранить изменения</button>
            <a href="{{ route('admin.orders.index') }}" class="btn-cancel">Отмена</a>
        </div>
    </form>
</div>
@endsection