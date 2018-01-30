@extends('layouts.app')
@section('menu')
<ol class="breadcrumb m-0 p-0">
    <li class="breadcrumb-item active"><a href="{{ url('/') }}">Home</a></li>
	<li class="breadcrumb-item active" aria-current="page">Studenten</li>
</ol>
@endsection
@section('content')
<a class="btn btn-primary mt-3 mb-3" href="{{ url('/student/add') }}">
	<span>Student toevoegen</span>
</a>
<studentsearch></studentsearch>
<studentlist></studentlist>
@endsection