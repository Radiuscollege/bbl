@extends('layouts.app') 
@section('menu')
    <div class="container">
        <nav aria-label="breadcrumb" role="navigation">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Home</li>
            </ol>
        </nav>
    </div>
@endsection 
@section('content')
<div class="links-container">
	<a class="btn btn-outline-primary" href="{{ url('/module') }}">
		<span>Modules</span>
	</a>
	<a class="btn btn-outline-primary" href="{{ url('/student') }}">
		<span>Studenten</span>
	</a>
	<a class="btn btn-outline-primary" href="{{ url('/statistics') }}">
		<span>Statistieken</span>
	</a>
</div>
@endsection