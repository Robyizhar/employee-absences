@extends('layout.admin.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Rekapitulasi Absensi</h4>

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
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped" id="rekapTable">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Departemen</th>
                    <th>Karyawan</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Terlambat</th>
                    <th>Pulang Cepat</th>
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
});
</script>
@endpush
