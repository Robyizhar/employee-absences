<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>

	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	{{-- <meta name="author" content="SemiColonWeb" /> --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $icon = $data['layout']['setting']['favicon']
    @endphp
    <link rel="icon" href="{{ asset('storage/'.$icon) }}" sizes="32x32">
    @stack('meta')
	<!-- Stylesheets ============================================= -->
	@include('layout.front.partials.style')
	<!-- Document Title ============================================= -->
    @stack('title')
</head>

<body class="stretched device-xl has-plugin-easing has-plugin-bootstrap has-plugin-swiper has-plugin-animations has-plugin-flexslider has-plugin-carousel">

	<!-- Document Wrapper ============================================= -->
	<div id="wrapper" class="clearfix">

		<!-- Header ============================================= -->
        @include('layout.front.partials.header')

        @if (Request::segment(1) == '')
            <!-- Slider ============================================= -->
            @include('layout.front.partials.slider')
        @endif

		<!-- Content ============================================= -->
        @yield('content')
        @php $voting = $data['layout']['sidebar'] @endphp
        <div class="modal" data-modal="trigger-2">
            <article class="content-wrapper">
                <button class="close"></button>
                <header class="modal-header">
                    <h2 class="title">Voting Penilaian</h2>
                </header>
                <div class="data-voting" style="height: 400px; width: 100%;">
                </div>
                <footer class="modal-footer">

                </footer>
            </article>
        </div>
        <div style="display: none;">
            <form action="" id="get-votes" method="post">
                @csrf
                <input type="hidden" name="question_id" value="{{ $voting['question']->id }}">
            </form>
        </div>
        <div class="form-process">
            <div class="css3-spinner">
                <div class="css3-spinner-scaler" style="background-color: #36385e; position: sticky;"></div>
            </div>
        </div>
		<!-- Footer ============================================= -->
        @include('layout.front.partials.footer')

	</div>
	<!-- Document Wrapper End ============================================= -->
	<!-- Whatsapp Widget ============================================= preg_replace('/[^0-9]/', '', $whatsapp); -->
    @php $whatsapp = $data['layout']['setting']['whatsapp'] @endphp
    <a id="iconTop" title="Contact via Whatsapp" class="col-2 col-lg-3 col-sm-2 p-0 mt-4" href="https://api.whatsapp.com/send?phone=+{{ preg_replace('/[^0-9]/', '', $whatsapp) }}&amp;text=Hallo SMK N 1 Indramayu!" target="_blank" rel="noopener noreferrer">
        <img data-animate="headShake" width="auto" height="auto" src="{{ asset('assets/front/images/whatsapp.png') }}" alt="" class="headShake animated">
    </a>
	<!-- Go To Top ============================================= -->
	<div id="gotoTop" class="icon-angle-up"></div>

	@include('layout.front.partials.script')

</body>
</html>
