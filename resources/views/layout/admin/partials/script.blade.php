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

@stack('script')
