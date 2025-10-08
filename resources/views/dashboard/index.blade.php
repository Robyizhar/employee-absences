@extends('layout.admin.app')
@section('content')
<div class="page-content">
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Welcome to Dashboard</h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            {{-- <div class="input-group date datepicker wd-200 me-2 mb-2 mb-md-0" id="dashboardDate">
                <span class="input-group-text input-group-addon bg-transparent border-primary"><i data-feather="calendar" class=" text-primary"></i></span>
                <input type="text" class="form-control border-primary bg-transparent">
            </div>
            <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0 maintenence mr-3">
                <i class="btn-icon-prepend" data-feather="download-cloud"></i>
                Download Report
            </button> --}}
            <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0 ml-2" id="refresh-btn">
                <i class="btn-icon-prepend" data-feather="refresh-ccw"></i>
                Refresh Data
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="dTable">
                            <thead>
                                <tr>
                                    <th class="pt-0">#</th>
                                    <th class="pt-0">Nama</th>
                                    <th class="pt-0">Scan Time</th>
                                    <th class="pt-0">Status</th>
                                    <th class="pt-0">Mesin</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- row -->
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
        showLoader();

        $.getJSON('/absence/list', { last_id: lastId }, function(res) {
            let rows = '';
            if (res.data.length > 0) {

                $.each(res.data, function(i, log) {

                    rows += `
                    <tr>
                        <td>${i + 1}</td>
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
            hideLoader();
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

</script>
@endpush
