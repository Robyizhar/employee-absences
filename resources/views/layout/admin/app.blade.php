<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Responsive HTML Admin Dashboard Template based on Bootstrap 5">
	<meta name="author" content="NobleUI">
	<meta name="keywords" content="nobleui, bootstrap, bootstrap 5, bootstrap5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Absence</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- End fonts -->
    <!-- End layout styles -->

    <link rel="shortcut icon" href="../assets/images/favicon.png" />
    {{-- <link rel="icon" href="{{ asset('storage/'.$logo['favicon']) }}" sizes="32x32"> --}}
        @include('layout.admin.partials.style')
</head>
<body>
	<div class="main-wrapper">
        @include('layout.admin.partials.sidebar')
		<div class="page-wrapper">
            @include('layout.admin.partials.header')

            <!--begin::Content-->
            @yield('content')
            <!--end::Content-->

            @include('layout.admin.partials.session')
            @include('layout.admin.partials.footer')
		</div>
	</div>


    @include('layout.admin.partials.script')
	<!-- End custom js for this page -->
</body>
</html>
