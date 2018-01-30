@extends('layouts.app') 
@section('menu')
<ol class="breadcrumb m-0 p-0">
    <li class="breadcrumb-item active" aria-current="page">Home</li>
</ol>
@endsection 
@section('content')
<studentsearch></studentsearch>
<div class="jumbotron">
	<h1 class="display-4">Progress Tracker</h1>
	<p class="lead">Dit is een app om de voortang van AMO BBL studenten te beheren.</p>
	<hr class="my-4">
	<p class="lead text-center">
	  <a class="btn btn-outline-primary" href="{{ url('/module') }}">
		<span>Modules</span>
	  </a>
	  <a class="btn btn-outline-primary" href="{{ url('/student') }}">
	  	<span>Studenten</span>
	  </a>
	  <a class="btn btn-outline-primary" href="{{ url('/statistics') }}">
	  	<span>Statistieken</span>
	  </a>
	</p>
</div>
@endsection