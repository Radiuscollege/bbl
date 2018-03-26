@extends('layouts.app')
@section('menu')
<ol class="breadcrumb m-0 p-0">
    <li class="breadcrumb-item active"><a href="{{ url('/') }}">Home</a></li>
	<li class="breadcrumb-item active"><a href="{{ url('/student') }}">Studenten</a></li>
	<li class="breadcrumb-item active" aria-current="page">Zoeken</li>
</ol>
@endsection
@section('content')
<studentsearch></studentsearch>
<studentsearchresult></studentsearchresult>
@endsection