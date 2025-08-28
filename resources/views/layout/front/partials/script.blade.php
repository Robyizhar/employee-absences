<script>
    function holdClick(event) {
        event.preventDefault();
    }
</script>

<!-- JavaScripts ============================================= -->
<script src="{{ asset('assets/front/js/jquery.js') }}"></script>
<script src="{{ asset('assets/front/js/plugins.js') }}"></script>
<script src="{{ asset('assets/front/js/plugins.infinitescroll.js') }}"></script>
{{-- <script src="owlcarousel/owl.carousel.min.js"></script> --}}

<!-- Footer Scripts ============================================= -->
<script src="{{ asset('assets/front/js/functions.js') }}"></script>
<script src="{{ asset('assets/front/demos/nonprofit/js/events.js') }}"></script>

<!-- SLIDER REVOLUTION 5.x SCRIPTS  -->
<script src="{{ asset('assets/front/include/rs-plugin/js/jquery.themepunch.tools.min.js') }}"></script>
<script src="{{ asset('assets/front/include/rs-plugin/js/jquery.themepunch.revolution.min.js') }}"></script>

{{-- <script src="{{ asset('assets/front/include/rs-plugin/js/extensions/revolution.extension.actions.min.js') }}"></script>
<script src="{{ asset('assets/front/include/rs-plugin/js/extensions/revolution.extension.carousel.min.js') }}"></script>
<script src="{{ asset('assets/front/include/rs-plugin/js/extensions/revolution.extension.kenburn.min.js') }}"></script>
<script src="{{ asset('assets/front/include/rs-plugin/js/extensions/revolution.extension.layeranimation.min.js') }}"></script>
<script src="{{ asset('assets/front/include/rs-plugin/js/extensions/revolution.extension.migration.min.js') }}"></script>
<script src="{{ asset('assets/front/include/rs-plugin/js/extensions/revolution.extension.navigation.min.js') }}"></script>
<script src="{{ asset('assets/front/include/rs-plugin/js/extensions/revolution.extension.parallax.min.js') }}"></script>
<script src="{{ asset('assets/front/include/rs-plugin/js/extensions/revolution.extension.slideanims.min.js') }}"></script>
<script src="{{ asset('assets/front/include/rs-plugin/js/extensions/revolution.extension.video.min.js') }}"></script> --}}

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    const BASE_URL = `{!! url('/') !!}/`
</script>
@include('sweetalert::alert')
@stack('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

    $(document).on("click", "#lihat-jawaban", function(e) {
        e.preventDefault();
        let url = `{{ route('get-vote') }}`;
        var formData = new FormData($('#get-votes')[0]);
        $('.form-process').css('display', 'block');
        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (response) {
                getVoteStatistic(response.label, response.value);
                $('.form-process').css('display', 'none');
            }
        });
    });

    function getVoteStatistic(label, value) {
        const modal = document.querySelector(`[data-modal=trigger-2]`);
        const contentWrapper = modal.querySelector('.content-wrapper');
        const close = modal.querySelector('.close');
        close.addEventListener('click', () => modal.classList.remove('open'));
        modal.addEventListener('click', () => modal.classList.remove('open'));
        contentWrapper.addEventListener('click', (e) => e.stopPropagation());
        modal.classList.toggle('open');
        $('#voteChart').remove();
        $('.data-voting').append("<canvas id='voteChart' width='400' height='400'></canvas>");
        const ctx = document.getElementById('voteChart').getContext('2d');
        const voteChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: label,
                datasets: [{
                    label: 'Polling Penilaian Pengunjung',
                    data: value,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.2)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 2)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

</script>
<script>
    $('#form-polling').submit(function (e) {
        e.preventDefault();
        var formData = new FormData($('#form-polling')[0]);
        var answer_id = $('.answer_id').val();
        let url = $('#form-polling').attr('action')
        let loader = $('.middle');
        let alert_success = $('.success-vote');
        $('#vote-area').html(loader);
        $.ajax({
            url : url,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function(response) {
                $('#vote-area').html(alert_success);
            },error: function (jqXHR, textStatus, errorThrown) {

            }
        });
    });
</script>
@if (Request::segment(1) == '')
<script src="{{ asset('assets/front/js/slider.js') }}"></script>
@endif
<script>

    // Navbar on hover
    $('.nav.tab-hover a.nav-link').hover(function() {
        $(this).tab('show');
    });

    // Current Date
    var weekday = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],
        month = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
        a = new Date();

        jQuery('.date-today').html( weekday[a.getDay()] + ', ' + month[a.getMonth()] + ' ' + a.getDate() );

    // Infinity Scroll
    jQuery(window).on( 'load', function(){

        var $container = $('.infinity-wrapper');

        $container.infiniteScroll({
            path: '.load-next-portfolio',
            button: '.load-next-portfolio',
            scrollThreshold: false,
            history: false,
            status: '.page-load-status'
        });

        $container.on( 'load.infiniteScroll', function( event, response, path ) {
            var $items = $( response ).find('.infinity-loader');
            // append items after images loaded
            $items.imagesLoaded( function() {
                $container.append( $items );
                $container.isotope( 'insert', $items );
                setTimeout( function(){
                    SEMICOLON.widget.loadFlexSlider();
                }, 1000 );
            });
        });

    });

    $(window).on( 'pluginCarouselReady', function(){
        $('#oc-news').owlCarousel({
            items: 1,
            margin: 20,
            dots: false,
            nav: true,
            navText: ['<i class="icon-angle-left"></i>','<i class="icon-angle-right"></i>'],
            responsive:{
                0:{ items: 1,dots: true, },
                576:{ items: 1,dots: true },
                768:{ items: 2,dots:true },
                992:{ items: 2 },
                1200:{ items: 3 }
            }
        });
    });

</script>
