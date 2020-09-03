<!DOCTYPE html>
<html lang="id">
	<head>
		<!-- Required meta tags -->
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		@yield('seo')

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
		<link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
		<link rel="stylesheet" href="{{ asset('styles/blog.css') }}">
		@stack('styles')

		<title>@yield('page_title')</title>

		<style type="text/css">
			body {
				padding-top: 56px;
			}
		</style>
	</head>
	<body>
		@include('apps.blog.partials.navbar')

		<main class="mt-2">
			@yield('content')
		</main>

		@include('apps.blog.partials.footer')

		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script src="{{ asset('vendor/jquery/jquery-3.5.1.min.js') }}"></script>
		<script src="{{ asset('vendor/popper/popper.min.js') }}"></script>
		<script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
		@stack('scripts')

		<!-- Optional JavaScript -->
	</body>
</html>