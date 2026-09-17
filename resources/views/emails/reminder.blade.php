@extends('emails.layout')

@section('heading', $participant->event->title ?? 'Напоминание')

@section('content')
    <p style="margin:0 0 8px;color:#999;font-size:14px;">Напоминание: мероприятие завтра!</p>
    <p style="margin:0 0 16px;color:#333;font-size:16px;">Здравствуйте, {{ $participant->name }}!</p>
    <p style="margin:0 0 24px;color:#666;font-size:14px;line-height:1.6;">
        Напоминаем, что завтра начинается мероприятие <strong>{{ $participant->event->title }}</strong>.
    </p>
    <div style="background:#f9f9f9;border-radius:8px;padding:20px 16px;margin:0 0 24px;font-size:14px;color:#666;">
        <p style="margin:0 0 6px;"><strong>Дата:</strong> {{ $participant->event->start_date->format('d.m.Y H:i') }}</p>
        @if($participant->event->venue_name)
            <p style="margin:0 0 6px;"><strong>Место:</strong> {{ $participant->event->venue_name }}</p>
        @endif
        @if($participant->event->venue_address)
            <p style="margin:0;"><strong>Адрес:</strong> {{ $participant->event->venue_address }}</p>
        @endif
    </div>
    <p style="margin:0 0 24px;color:#666;font-size:14px;line-height:1.6;">
        Не забудьте взять с собой билет с QR-кодом. Он понадобится на входе.
    </p>
    <div style="text-align:center;">
        <a href="{{ $ticketUrl }}" style="display:inline-block;padding:14px 32px;background:#667eea;color:#fff;text-decoration:none;border-radius:6px;font-size:16px;font-weight:500;">Открыть билет</a>
    </div>
@endsection