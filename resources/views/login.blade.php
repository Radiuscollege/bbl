@extends('layouts.app')
@section('content')
@auth
    <a class="d-flex justify-content-center" href="{{ url('/') }}">Je bent ingelogd, klik hier om naar de homepagina te gaan</a>
@else
    <a class="d-flex justify-content-center display-4" href="{{ url('/amoclient/redirect') }}">Login</a>
@endauth
@endsection
