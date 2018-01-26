@extends('layouts.app') @section('menu')
<div class="col-12 col-md-8">
	<nav aria-label="breadcrumb" role="navigation">
		<ol class="breadcrumb">
			<li class="breadcrumb-item active">
				<a href="{{ url('/') }}">Home</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">Module</li>
		</ol>
	</nav>
</div>
@endsection @section('content')
<cohort></cohort>
<a class="btn btn-primary mb-3 mt-3" href="{{ url('/module/add') }}">
	<span>Module toevoegen</span>
</a>
<modulelist></modulelist>
@endsection