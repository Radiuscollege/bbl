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
		<div class="row d-print-none">
			@yield('menu') @auth @if (Route::has('login'))
			<div class="col-6 col-md-4">
				<a class="float-right" href="{{ url('/amoclient/logout') }}">Log uit</a>
				@else
				<a class="float-right" href="{{ url('/amoclient/redirect') }}">Log in</a>
				@endauth
			</div>
			@endif
		</div>
		@yield('content')
	</div>
	<script src="{{ asset('js/app.js') }}"></script>
</body>

</html>