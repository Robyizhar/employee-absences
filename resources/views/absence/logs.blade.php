@extends('layout.admin.app')

@section('content')
<div class="page-content">
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
        <h4 class="mb-3 mb-md-0">Absence Page</h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            {{-- <div class="input-group date datepicker wd-200 me-2 mb-2 mb-md-0" id="dashboardDate">
                <span class="input-group-text input-group-addon bg-transparent border-primary"><i data-feather="calendar" class=" text-primary"></i></span>
                <input type="text" class="form-control border-primary bg-transparent">
            </div>
            <button type="button" class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0">
                <i class="btn-icon-prepend" data-feather="printer"></i>
                Print
            </button> --}}
            <button type="button" class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0  me-2">
                <i class="btn-icon-prepend" data-feather="download-cloud"></i>
                Download Report
            </button>
            <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0" id="refresh-btn">
                <i class="btn-icon-prepend" data-feather="refresh-ccw"></i>
                Refresh Data
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline mb-2">
                            <h6 class="card-title mb-0">Projects</h6>
                            <div class="dropdown mb-2">
                            <button class="btn p-0" type="button" id="dropdownMenuButton7" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton7">
                                <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="eye" class="icon-sm me-2"></i> <span class="">View</span></a>
                                <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="edit-2" class="icon-sm me-2"></i> <span class="">Edit</span></a>
                                <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="trash" class="icon-sm me-2"></i> <span class="">Delete</span></a>
                                <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="printer" class="icon-sm me-2"></i> <span class="">Print</span></a>
                                <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="download" class="icon-sm me-2"></i> <span class="">Download</span></a>
                            </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="dTable">
                                <thead>
                                    <tr>
                                        <th class="pt-0">ID</th>
                                        <th class="pt-0">Nama</th>
                                        <th class="pt-0">Scan Time</th>
                                        <th class="pt-0">Status</th>
                                        <th class="pt-0">Mesin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="pt-0" colspan="5">Data Kosong</td>
                                    </tr>
                                </tbody>
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
    const appTimezone = "{{ config('app.absence_timezone') }}";
    // console.log("Timezone Laravel:", appTimezone);

    function loadAbsences() {
        if (loading) return;
        loading = true;

        $.getJSON('/absence/list', { last_id: lastId }, function(res) {
            let rows = '';
            if (res.data.length > 0) {
                $('#dTable tbody').empty();
                $.each(res.data, function(i, log) {
                    console.log(log.employee.name, log.late_minutes);

                    rows += `
                    <tr>
                        <td>${log.id}</td>
                        <td>${log.employee ? log.employee.name : '-'}</td>
                        <td>
                            ${new Date(log.scan_time).toLocaleString("id-ID", {
                                timeZone: appTimezone,
                                day: "2-digit",
                                month: "long",
                                year: "numeric",
                                hour: "2-digit",
                                minute: "2-digit",
                                second: "2-digit"
                            })}
                        </td>
                        <td>
                            <span class="badge ${log.status === 'IN' ? 'bg-success' : 'bg-warning'}">
                            ${log.status}
                            </span>
                        </td>
                        <td>${log.machine ? log.machine.serial_number : '-'}</td>
                        <td>
                            ${(() => {
                                let text = "";
                                if (log.is_duplicate) {
                                    text += `<span class="badge bg-secondary">Duplicate</span><br>`;
                                }

                                // Tampilkan status keterlambatan atau pulang cepat
                                // if (log.late_seconds && log.late_seconds > 0) {
                                //     text += `<span class="badge bg-danger">Terlambat ${Math.ceil(log.late_seconds / 60)} menit</span><br>`;
                                // } else if (log.early_seconds && log.early_seconds > 0) {
                                //     text += `<span class="badge bg-info text-dark">Datang cepat ${Math.ceil(log.early_seconds / 60)} menit</span><br>`;
                                // }

                                if (log.late_minutes && log.late_minutes > 0) {
                                    const duration = formatMinutes(log.late_minutes);
                                    text += `<span class="badge bg-danger">Terlambat ${duration}</span><br>`;
                                }

                                if (log.early_leave_minutes && log.early_leave_minutes > 0) {
                                    const duration = formatMinutes(log.early_leave_minutes);
                                    text += `<span class="badge bg-warning text-dark">Pulang cepat ${duration}</span>`;
                                }

                                return text || '-';
                            })()}
                        </td>
                    </tr>`;

                });
                $('#dTable tbody').append(rows);

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
        $(this).html('<div class="loader"></div>');
        loadAbsences();
    });

    loadAbsences();

    function formatMinutes(minutes) {
        minutes = Math.abs(minutes || 0); // pastikan positif dan tidak null

        const h = Math.floor(minutes / 60);
        const m = minutes % 60;

        let parts = [];
        if (h > 0) parts.push(`${h} jam`);
        if (m > 0) parts.push(`${m} menit`);
        if (parts.length === 0) parts.push('0 menit');

        return parts.join(' ');
    }



    // $('#refresh-btn').click(function() {
    //     let btn = $(this);
    //     btn.prop('disabled', true).html('<i data-feather="loader" class="spin"></i> Refreshing...');

    //     $.ajax({
    //         url: '/absence/refresh',
    //         type: 'GET',
    //         success: function(res) {
    //             console.log(res);
    //             btn.prop('disabled', false).html('<i class="btn-icon-prepend" data-feather="refresh-ccw"></i> Refresh Data');
    //             feather.replace(); // refresh icon feather
    //             setTimeout(function() {
    //                 lastId = null;
    //                 loadAbsences();
    //             }, 3000);
    //         },
    //         error: function() {
    //             alert('Gagal refresh data!');
    //             btn.prop('disabled', false).html('<i class="btn-icon-prepend" data-feather="refresh-ccw"></i> Refresh Data');
    //             feather.replace();
    //         }
    //     });
    // });

</script>
@endpush



