@extends('layout.admin.app')

@section('content')
<div class="page-content">

    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Rekapitulasi Absensi</h4>
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
            {{-- <button type="button" class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0  me-2">
                <i class="btn-icon-prepend" data-feather="download-cloud"></i>
                Download Report
            </button>
            <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0" id="refresh-btn">
                <i class="btn-icon-prepend" data-feather="refresh-ccw"></i>
                Refresh Data
            </button> --}}
        </div>
    </div>
    {{-- <h4 class="mb-4">Rekapitulasi Absensi</h4> --}}

    <div class="card mb-4">
        <div class="card-body">
            <form id="filterForm" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" name="start_date" id="start_date" value="{{ now()->startOfMonth()->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" name="end_date" id="end_date" value="{{ now()->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Departemen</label>
                    <select class="form-select" name="department_id" id="department_id">
                        <option value="">Semua</option>
                        @foreach($departments as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Nama Karyawan</label>
                    <input type="text" class="form-control" name="employee_name" id="employee_name" placeholder="Cari nama karyawan...">
                </div>
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i data-feather="filter"></i> Tampilkan
                    </button>
                    <button id="btnExport" class="btn btn-success">
                        <i data-feather="file-text"></i> Export Excel
                    </button>

                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped" id="rekapTable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Department</th>
                    <th>Employee</th>
                    <th>Check-in Time</th>
                    <th>Closing Time</th>
                    <th>Late</th>
                    <th>Leaving Early</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection

@push('script')
<script>

$(document).ready(function () {
    feather.replace();
    loadRekap();

    $('#filterForm').on('submit', function(e){
        e.preventDefault();
        loadRekap();
    });

    function loadRekap() {
        let params = $('#filterForm').serialize();

        $.getJSON('{{ url("absence/recapitulation/list") }}', params, function(res){
            let rows = '';
            res.data.forEach(r => {
                let inTime = r.first_in ? new Date(r.first_in + "Z").toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit',second:'2-digit'}) : '-';
                let outTime = r.last_out ? new Date(r.last_out + "Z").toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit',second:'2-digit'}) : '-';
                let late = r.total_late ? `${r.total_late} menit` : '-';
                let early = r.total_early ? `${r.total_early} menit` : '-';

                rows += `
                    <tr>
                        <td>${r.date}</td>
                        <td>${r.department_name ?? '-'}</td>
                        <td>${r.employee_name}</td>
                        <td>${inTime}</td>
                        <td>${outTime}</td>
                        <td>${late}</td>
                        <td>${early}</td>
                    </tr>
                `;
            });

            $('#rekapTable tbody').html(rows || '<tr><td colspan="7" class="text-center">Tidak ada data</td></tr>');
            feather.replace();
        });
    }

    $('#btnExport').on('click', function () {
        const btn = $(this);
        btn.prop('disabled', true).text('Mempersiapkan file...');

        setTimeout(() => {
            const params = $('#filterForm').serialize();
            const url = `/absence/recapitulation/export?${params}`;
            window.open(url, '_blank');
            btn.prop('disabled', false).text('Export Excel');
        }, 3000);
    });

});
</script>
@endpush
