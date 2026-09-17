@extends('emails.layout')

@section('heading', $participant->event->title ?? 'Ваш билет')

@section('content')
    <p style="margin:0 0 16px;color:#333;font-size:16px;">Уважаемый участник мероприятия!</p>
    <p style="margin:0 0 24px;color:#666;font-size:14px;line-height:1.6;">
        Ваш билет на мероприятие <strong>{{ $participant->event->title }}</strong> готов.
        Предъявите QR-код на входе.
    </p>
    @if($participant->event->venue_name || $participant->event->venue_address)
        <div style="background:#f9f9f9;border-radius:8px;padding:20px 16px;margin:0 0 24px;">
            <p style="margin:0 0 12px;font-size:14px;color:#666;text-transform:uppercase;font-weight:600;">Место проведения</p>
            @if($participant->event->venue_name)
                <p style="margin:0 0 6px;font-size:18px;color:#333;font-weight:600;">{{ $participant->event->venue_name }}</p>
            @endif
            @if($participant->event->venue_address)
                <p style="margin:0;font-size:16px;color:#666;">{{ $participant->event->venue_address }}</p>
            @endif
        </div>
    @endif
    <div style="text-align:center;margin:0 0 16px;">
        <a href="{{ $ticketUrl }}" style="display:inline-block;padding:14px 32px;background:#667eea;color:#fff;text-decoration:none;border-radius:6px;font-size:16px;font-weight:500;">Открыть билет</a>
    </div>
    <div style="text-align:center;margin:0 0 24px;">
        <a href="{{ $ticketUrl }}/pdf" style="display:inline-block;padding:10px 24px;background:transparent;color:#667eea;text-decoration:none;border-radius:6px;font-size:14px;border:1px solid #667eea;">Скачать PDF</a>
    </div>
    <p style="margin:0;font-size:12px;color:#999;text-align:center;">Если кнопки не работают, скопируйте ссылку: {{ $ticketUrl }}</p>
@endsection