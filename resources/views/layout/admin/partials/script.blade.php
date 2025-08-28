<script>var hostUrl = "{{ asset('assets/admin/') }}";</script>
<script src="{{ asset('assets/admin/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('assets/admin/js/scripts.bundle.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/custom/datatables/datatables.bundle.js') }}"></script>
{{-- <script src="{{ asset('assets/admin/js/custom/apps/ecommerce/catalog/save-product.js') }}"></script> --}}
<script src="{{ asset('assets/admin/js/widgets.bundle.js') }}"></script>
<script src="{{ asset('assets/admin/js/custom/widgets.js') }}"></script>
<script src="{{ asset('assets/admin/js/custom/apps/chat/chat.js') }}"></script>
<script src="{{ asset('assets/admin/js/custom/utilities/modals/upgrade-plan.js') }}"></script>
<script src="{{ asset('assets/admin/js/custom/utilities/modals/create-app.js') }}"></script>
<script src="{{ asset('assets/admin/js/custom/utilities/modals/users-search.js') }}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.js"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@include('sweetalert::alert')
@stack('script')
<script>
    $(document).on('click', '.btn-delete', function(e){
        e.preventDefault();
        let detele_url = $(this).attr("href");
        Swal.fire({
            title: 'Hapus data ini ?',
            text: "Anda tidak akan dapat memulihkan data ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Hapus!'
            }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = detele_url;
                // $('#datatable').DataTable().ajax.reload();
                // Swal.fire( 'Deleted!', 'Your file has been deleted.', 'success' )
            }
        })
    });

    $(document).on('click', '.refresh-website', function(e){
        e.preventDefault();
        let detele_url = $(this).attr("href");
        let title = $(this).find('.menu-title').html();
        Swal.fire({
            title: title + ' ?',
            text: "Beberapa fungsi mungkin akan terganggu !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya!'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "GET",
                    url: detele_url,
                    cache: false,
                    success: function(data){
                        Swal.fire( 'Berhasil!', data+' !', 'success' );
                    }
                });
            }
        })
    });
</script>
