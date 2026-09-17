@extends('emails.layout')

@section('heading', 'Код подтверждения')

@section('content')
    <p style="margin:0 0 16px;color:#333;font-size:16px;">Здравствуйте, {{ $participant->name }}!</p>
    <p style="margin:0 0 24px;color:#666;font-size:14px;line-height:1.6;">
        Вы запросили восстановление билета на мероприятие
        <strong>{{ $participant->event->title }}</strong>.
        Используйте следующий код для подтверждения:
    </p>
    <div style="text-align:center;margin:0 0 24px;padding:24px;background:#f9f9f9;border-radius:8px;">
        <span style="font-size:36px;font-weight:bold;letter-spacing:6px;color:#333;">{{ $participant->verification_code }}</span>
    </div>
    <p style="margin:0 0 8px;font-size:13px;color:#666;text-align:center;">Введите этот код на странице восстановления</p>
    <p style="margin:0;font-size:12px;color:#999;text-align:center;">Код действителен в течение 15 минут</p>
@endsection