@extends('layout.admin.app')

@section('content')
<div class="page-content">
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Company Page</h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            {{-- <div class="input-group date datepicker wd-200 me-2 mb-2 mb-md-0" id="dashboardDate">
                <span class="input-group-text input-group-addon bg-transparent border-primary"><i data-feather="calendar" class=" text-primary"></i></span>
                <input type="text" class="form-control border-primary bg-transparent">
            </div>
            <button type="button" class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0">
                <i class="btn-icon-prepend" data-feather="printer"></i>
                Print
            </button>
            <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
                <i class="btn-icon-prepend" data-feather="download-cloud"></i>
                Download Report
            </button> --}}
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="dTable">
                                <thead>
                                    <tr>
                                        <th class="pt-0">ID</th>
                                        <th class="pt-0">Name</th>
                                        <th class="pt-0">Company Code</th>
                                        <th class="pt-0">Address</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <button class="btn btn-primary" id="load-more">Load More</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    let lastId = null;
    let loading = false;

    function loadUsers() {
        $('#load-more').html('<div class="loader"></div>');

        if (loading) return;
        loading = true;

        $.getJSON('/company/list', { last_id: lastId }, function(res) {
            let rows = '';
            $.each(res.data, function(i, user) {
                rows += `<tr>
                    <td>${user.id}</td>
                    <td>${user.name}</td>
                    <td>${user.code}</td>
                    <td>${user.address}</td>
                </tr>`;
            });
            $('#dTable tbody').append(rows);

            // update lastId
            if (res.data.length > 0) {
                lastId = res.data[res.data.length - 1].id;
            }

            // hide button kalau tidak ada lagi
            if (!res.hasMore) {
                $('#load-more').hide();
            }
            $('#load-more').html('Load More');
            loading = false;
        });
    }

    $('#load-more').click(function() {
        // $(this).html('<div class="loader"></div>');
        loadUsers();
    });

    // load awal
    loadUsers();

</script>
@endpush
