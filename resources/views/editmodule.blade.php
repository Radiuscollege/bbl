@extends('layouts.app') @section('menu')
<ol class="breadcrumb m-0 p-0">
	<li class="breadcrumb-item active">
		<a href="{{ url('/') }}">Home</a>
	</li>
	<li class="breadcrumb-item active">
		<a href="{{ url('/module') }}">Modules</a>
	</li>
	<li class="breadcrumb-item active" aria-current="page">Wijzigen</li>
</ol>
@endsection @section('content')
<moduleform></moduleform>
@endsection