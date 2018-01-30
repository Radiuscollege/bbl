@extends('layouts.app')
@section('content')
@auth
    <a class="d-flex justify-content-center" href="{{ url('/') }}">Ga naar de startpagina</a>
@else
    <a class="d-flex justify-content-center" href="{{ url('/amoclient/redirect') }}">Login</a>
@endauth
@endsection
