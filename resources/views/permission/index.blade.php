@extends('layout.admin.app')

@section('content')
<div class="page-content">
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Permission Page</h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
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
                                        <th class="pt-0">Nama</th>
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

    function loadData() {
        $('#load-more').html('<div class="loader"></div>');

        if (loading) return;
        loading = true;

        $.getJSON('/permission/list', { last_id: lastId }, function(res) {
            let rows = '';
            $.each(res.data, function(i, user) {
                rows += `<tr>
                    <td>${user.id}</td>
                    <td>${user.name}</td>
                </tr>`;
            });
            $('#dTable tbody').append(rows);

            if (res.data.length > 0) {
                lastId = res.data[res.data.length - 1].id;
            }

            if (!res.hasMore) {
                $('#load-more').hide();
            }
            $('#load-more').html('Load More');
            loading = false;
        });
    }

    $('#load-more').click(function() {
        loadData();
    });

    // load awal
    loadData();

</script>
@endpush
