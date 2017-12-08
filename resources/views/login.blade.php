@extends('layouts.app')
@section('content')
@auth
    <a href="{{ url('/') }}">Ga naar de startpagina</a>
@else
    <a href="{{ url('/amoclient/redirect') }}">Login</a>
@endauth
@endsection
