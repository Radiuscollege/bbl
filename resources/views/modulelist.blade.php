@extends('layouts.app') @section('menu')
<ol class="breadcrumb m-0 p-0">
	<li class="breadcrumb-item active">
		<a href="{{ url('/') }}">Home</a>
	</li>
	<li class="breadcrumb-item active" aria-current="page">Module</li>
</ol>
@endsection @section('content')
<a class="btn btn-primary mb-3 mt-3" href="{{ url('/module/add') }}">
	<span>Module toevoegen</span>
</a>
<cohort></cohort>
@endsection