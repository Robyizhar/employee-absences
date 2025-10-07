{{-- <script>var hostUrl = "{{ asset('assets/admin/') }}";</script> --}}
{{-- <script src="{{ asset('assets/admin/js/custom/apps/ecommerce/catalog/save-product.js') }}"></script> --}}
<!-- core:js -->
<script src=" {{ url('assets/vendors/core/core.js') }}"></script>
<!-- endinject -->

<!-- Plugin js for this page -->
<script src=" {{ url('assets/vendors/chartjs/Chart.min.js') }}"></script>
<script src=" {{ url('assets/vendors/jquery.flot/jquery.flot.js') }}"></script>
<script src=" {{ url('assets/vendors/jquery.flot/jquery.flot.resize.js') }}"></script>
<script src=" {{ url('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src=" {{ url('assets/vendors/apexcharts/apexcharts.min.js') }}"></script>
<!-- End plugin js for this page -->

<!-- inject:js -->
<script src=" {{ url('assets/vendors/feather-icons/feather.min.js') }}"></script>
<script src=" {{ url('assets/js/template.js') }}"></script>
<!-- endinject -->

<!-- Custom js for this page -->
<script src=" {{ url('assets/js/dashboard-light.js') }}"></script>
<script src=" {{ url('assets/js/datepicker.js') }}"></script>

<script>
    $('.maintenence').click(function (e) {
        e.preventDefault();
        console.log("UNDER MAINTENANCE");
    });
</script>

<script>
(function($){
    // Fungsi manual

    // function showLoader () {
    //     $('#page-loader').removeClass('d-none');
    // }
    // function hideLoader () {
    //     $('#page-loader').addClass('d-none');
    // }


    window.showLoader = function() {
        $('#page-loader').removeClass('d-none');
        $('body').addClass('loader-active');
    };
    window.hideLoader = function() {
        $('#page-loader').addClass('d-none');
        $('body').removeClass('loader-active');
    };

    // // HANYA untuk request dengan loader: true
    // $(document).on('ajaxSend', function(e, xhr, settings){
    //     if (settings.loader === true) {
    //     xhr._useLoader = true;
    //     showLoader();
    //     }
    // });

    // // Sembunyikan hanya untuk request yang pakai loader
    // $(document).on('ajaxComplete ajaxError', function(e, xhr){
    //     if (xhr._useLoader) {
    //     hideLoader();
    //     xhr._useLoader = false;
    //     }
    // });
})(jQuery);
</script>

@stack('script')
