@extends('layouts.app')
@section('menu')
    <div class="container">
        <nav aria-label="breadcrumb" role="navigation">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="{{ url('/') }}">Home</a></li>
				<li class="breadcrumb-item active"><a href="{{ url('/student') }}">Studenten</a></li>
				<li class="breadcrumb-item active" aria-current="page">Beheren</li>
            </ol>
        </nav>
    </div>
@endsection
@section('content')
@endsection