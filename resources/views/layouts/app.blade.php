<html lang="{{ app()->getLocale() }}">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>Radius College Progress Tracker</title>

	<!-- Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

	<!-- Icons -->
	<script defer src="https://use.fontawesome.com/releases/v5.0.1/js/all.js"></script>

	<!-- Styles -->
	<link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('css/all.css') }}" rel="stylesheet" type="text/css">
</head>

<body>
	<div id="app" class="container">
		<div class="d-print-none">
			<nav aria-label="breadcrumb" class="breadcrumb d-flex justify-content-between">
				@yield('menu') 
				<span class="navbar-text p-0">
				@auth @if (Route::has('login'))
					<a class="float-right" href="{{ url('/amoclient/logout') }}">Log uit</a>
					@else
					<a class="float-right" href="{{ url('/amoclient/redirect') }}">Log in</a>
					@endauth
				@endif
    			</span>
			</nav>
		</div>
		@yield('content')
	</div>
	<script src="{{ asset('js/app.js') }}"></script>
</body>

</html>