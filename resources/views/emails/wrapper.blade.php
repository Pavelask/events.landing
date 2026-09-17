@extends('emails.layout')

@section('heading', $heading ?? 'Мероприятие')

@section('content')
    {!! $html !!}
@endsection