
<link href="https://fonts.googleapis.com/css2?family=Caveat+Brush&family=Poppins:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/front/css/bootstrap.css') }}" type="text/css" />
<link rel="stylesheet" href="{{ asset('assets/front/style.css') }}" type="text/css" />
<link rel="stylesheet" href="{{ asset('assets/front/css/dark.css') }}"  type="text/css" />

<link rel="stylesheet" href="{{ asset('assets/front/css/font-icons.css') }}" type="text/css" />
<link rel="stylesheet" href="{{ asset('assets/front/one-page/css/et-line.css') }}" type="text/css" />
<link rel="stylesheet" href="{{ asset('assets/front/css/animate.css') }}" type="text/css" />
<link rel="stylesheet" href="{{ asset('assets/front/css/magnific-popup.css') }}" type="text/css" />

<link rel="stylesheet" href="{{ asset('assets/front/css/custom.css') }}" type="text/css" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<link rel="stylesheet" href="{{ asset('assets/front/css/swiper.css') }}" type="text/css" />

<!-- Theme Color Stylesheet -->
<!-- <link rel="stylesheet" href="{{ asset('assets/front/css/colors.php?color=ffc10a') }}" type="text/css" /> -->

<!-- News Demo Specific Stylesheet -->
<link rel="stylesheet" href="{{ asset('assets/front/demos/news/news.css') }}" type="text/css" />
<link rel="stylesheet" href="{{ asset('assets/front/demos/coworking/coworking.css') }}" type="text/css" />
<link rel="stylesheet" href="{{ asset('assets/front/demos/nonprofit/css/fonts.css') }}" type="text/css" />
<link rel="stylesheet" href="{{ asset('assets/front/demos/nonprofit/nonprofit.css') }}" type="text/css" />
<!-- / -->

<!-- SLIDER REVOLUTION 5.x CSS SETTINGS -->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/front/include/rs-plugin/css/settings.css') }}" media="screen" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/front/include/rs-plugin/css/layers.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/front/include/rs-plugin/css/navigation.css') }}">

{{-- <link rel="stylesheet" href="owlcarousel/owl.carousel.min.css">
<link rel="stylesheet" href="owlcarousel/owl.theme.default.min.css"> --}}

<link rel="stylesheet" type="text/css" href="{{ asset('assets/front/owlcarousel/dist/assets/owl.carousel.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/front/owlcarousel/dist/assets/owl.theme.default.min.css') }}">

<style>
    /* Revolution Slider Styles */
    .hesperiden .tp-tab { border-bottom: 0; }
    .hesperiden .tp-tab:hover,
    .hesperiden .tp-tab.selected { background-color: #E5E5E5; }

</style>

@stack('style')
<link rel="stylesheet" href="{{ asset('assets/front/css/agenda.css') }}">
<style>
    .middle {
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        position: absolute;
    }
    .bar {
        width: 10px;
        height: 70px;
        background: #fff;
        display: inline-block;
        transform-origin: bottom center;
        border-top-right-radius: 20px;
        border-top-left-radius: 20px;
        /*   box-shadow:5px 10px 20px inset rgba(255,23,25.2); */
        animation: loader 1.2s linear infinite;
    }
    .bar1 {
        animation-delay: 0.1s;
    }
    .bar2 {
        animation-delay: 0.2s;
    }
    .bar3 {
        animation-delay: 0.3s;
    }
    .bar4 {
        animation-delay: 0.4s;
    }
    .bar5 {
        animation-delay: 0.5s;
    }
    .bar6 {
        animation-delay: 0.6s;
    }
    .bar7 {
        animation-delay: 0.7s;
    }
    .bar8 {
        animation-delay: 0.8s;
    }

    @keyframes loader {
        0% {
            transform: scaleY(0.1);
            background: ;
        }
        50% {
            transform: scaleY(1);
            background: yellowgreen;
        }
        100% {
            transform: scaleY(0.1);
            background: transparent;
        }
    }

    #iconTop {
        z-index: 599;
        position: fixed;
        width: 65px;
        height: 65px;
        font-size: 1.25rem;
        line-height: 36px;
        text-align: center;
        color: #FFF;
        top: auto;
        left: auto;
        right: 20px;
        bottom: 85px;
        cursor: pointer;
        border-radius: 2px;
    }
</style>
