@extends('layouts.app')
@section('content')
@auth
    <a class="d-flex justify-content-center" href="{{ url('/') }}">Je bent succesvol ingelogd, klik hier om naar de startpagina te gaan</a>
@else
    <a class="d-flex justify-content-center" href="{{ url('/amoclient/redirect') }}">Login</a>
@endauth
@endsection
