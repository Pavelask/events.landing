@extends('emails.layout')

@section('heading', 'Регистрация подтверждена')

@section('content')
    <p style="margin:0 0 16px;color:#333;font-size:16px;">Вы успешно зарегистрировались!</p>
    <p style="margin:0 0 24px;color:#666;font-size:14px;line-height:1.6;">
        Ваша регистрация на мероприятие <strong>{{ $eventTitle }}</strong> подтверждена.
    </p>
    <div style="background:#f9f9f9;border-radius:8px;padding:20px 16px;margin:0 0 24px;">
        <p style="margin:0 0 8px;font-size:14px;color:#666;">Номер регистрации:</p>
        <p style="margin:0;font-size:18px;font-weight:bold;color:#333;">#{{ $participant->id }}</p>
    </div>
    <p style="margin:0;font-size:13px;color:#999;text-align:center;">Билет будет отправлен отдельно.</p>
@endsection