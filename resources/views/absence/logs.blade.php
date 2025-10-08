@extends('layout.admin.app')

@section('content')
<div class="page-content">
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
        <h4 class="mb-3 mb-md-0">Absence Page</h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <button type="button" class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0  me-2 maintenence">
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
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="dTable">
                                <thead>
                                    <tr>
                                    <th class="pt-0">ID</th>
                                    <th class="pt-0">Name</th>
                                    <th class="pt-0">Scan Time</th>
                                    <th class="pt-0">Status</th>
                                    <th class="pt-0">Machine</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- <tr>
                                        <td class="pt-0" colspan="5">Empty Data</td>
                                    </tr> -->
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

                $.each(res.data, function(i, log) {

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

    $(document).on('click', '#refresh-btn', async function () {
        $('#dTable tbody').empty();
        $('#load-more').show();
        let btn = $(this);

        btn.prop('disabled', true).html('<i data-feather="loader" class="spin"></i> Refreshing...');
        feather.replace();

        lastId = null;

        try {
            await loadAbsences();
            btn.prop('disabled', false).html('<i class="btn-icon-prepend" data-feather="refresh-ccw"></i> Refresh Data');
            feather.replace();
        } catch (error) {
            console.error(error);
        }

    });



    // $('#refresh-btn').click(function() {
        // let btn = $(this);
        // btn.prop('disabled', true).html('<i data-feather="loader" class="spin"></i> Refreshing...');

        // $.ajax({
        //     url: '/absence/refresh',
        //     type: 'GET',
        //     success: function(res) {
        //         console.log(res);
        //         btn.prop('disabled', false).html('<i class="btn-icon-prepend" data-feather="refresh-ccw"></i> Refresh Data');
        //         feather.replace(); // refresh icon feather
        //         setTimeout(function() {
        //             lastId = null;
        //             loadAbsences();
        //         }, 3000);
        //     },
        //     error: function() {
        //         alert('Gagal refresh data!');
        //         btn.prop('disabled', false).html('<i class="btn-icon-prepend" data-feather="refresh-ccw"></i> Refresh Data');
        //         feather.replace();
        //     }
        // });
    // });

</script>
@endpush
