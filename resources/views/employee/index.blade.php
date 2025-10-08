@extends('layout.admin.app')
@push('style')
<link rel="stylesheet" href="{{ url("assets/vendors/sweetalert2/sweetalert2.min.css") }}">
@endpush

@section('content')
<div class="page-content">
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
        <h4 class="mb-3 mb-md-0">Employee Page</h4>
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
                                        <th class="pt-0">Kode Karyawan</th>
                                        <th class="pt-0">Departemen</th>
                                        <th class="pt-0">Perusahaan</th>
                                        <th class="pt-0">Aksi</th>
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

<!-- Modal Edit Employee -->
<div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="employeeForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="employeeModalLabel">Edit Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="employee_id" id="employee_id">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama</label>
                            <input type="text" id="employee_name" class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kode Karyawan</label>
                            <input type="text" id="employee_code" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Perusahaan</label>
                            <input type="text" id="company_name" class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Departemen</label>
                            <select name="department_id" id="department_id" class="form-select" required>
                                <option value="">-- Pilih Departemen --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
@push('script')
<script src="{{ url("assets/vendors/sweetalert2/sweetalert2.min.js") }}"></script>

<script>
    let lastId = null;
    let loading = false;

    function loadEmployees() {
        $('#load-more').html('<div class="loader"></div>');
        if (loading) return;
        loading = true;

        $.getJSON('/employee/list', { last_id: lastId }, function(res) {
            let rows = '';
            if (res.data.length > 0) {
                $('#dTable tbody').empty();
                $.each(res.data, function(i, emp) {
                    rows += `<tr>
                        <td>${emp.id}</td>
                        <td>${emp.name}</td>
                        <td>${emp.employee_code}</td>
                        <td>${emp.department ? emp.department.name : '-'}</td>
                        <td>${emp.company ? emp.company.name : '-'}</td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-employee"
                                data-id="${emp.id}"
                                data-name="${emp.name}"
                                data-code="${emp.employee_code}"
                                data-company="${emp.company ? emp.company.name : '-'}"
                                data-department_id="${emp.department_id}">
                                <i data-feather="edit-2" width="14" height="14"></i>
                            </button>
                        </td>
                    </tr>`;
                });
                $('#dTable tbody').append(rows);
                feather.replace();
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
        loadEmployees();
    });

    loadEmployees();

    $('#refresh-btn').click(function() {
        let btn = $(this);
        btn.prop('disabled', true).html('<i data-feather="loader" class="spin"></i> Refreshing...');

        $.ajax({
            url: '/employee/refresh',
            type: 'GET',
            success: function(res) {
                console.log(res);
                btn.prop('disabled', false).html('<i class="btn-icon-prepend" data-feather="refresh-ccw"></i> Refresh Data');
                feather.replace(); // refresh icon feather
                setTimeout(function() {
                    lastId = null;
                    loadEmployees();
                }, 3000);
            },
            error: function() {
                alert('Gagal refresh data!');
                btn.prop('disabled', false).html('<i class="btn-icon-prepend" data-feather="refresh-ccw"></i> Refresh Data');
                feather.replace();
            }
        });
    });

    $(document).on('click', '.edit-employee', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const code = $(this).data('code');
        const company = $(this).data('company');
        const department_id = $(this).data('department_id');

        $('#employeeModalLabel').text('Edit Employee');
        $('#employee_id').val(id);
        $('#employee_name').val(name);
        $('#employee_code').val(code);
        $('#company_name').val(company);
        $('#department_id').val(department_id);

        $('#employeeModal').modal('show');
    });

    $('#employeeForm').submit(function(e) {
        e.preventDefault();
        const id = $('#employee_id').val();
        const formData = $(this).serialize() + '&_method=PUT';

        $.ajax({
            url: `/employee/${id}`,
            type: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                department_id: $('#department_id').val()
            },
            success: function(res) {
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    });

                    $('#employeeModal').modal('hide');
                    loadEmployees(); // reload tabel
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat menyimpan perubahan.'
                });
            }
        });
    });



</script>
@endpush
