@extends('layout.admin.app')
@push('style')
<link rel="stylesheet" href="{{ url("assets/vendors/sweetalert2/sweetalert2.min.css") }}">
@endpush

@section('content')
<div class="page-content">
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Department Page</h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0" id="btnAddDepartment">
                <i class="btn-icon-prepend" data-feather="plus"></i> Add Department
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">Department List</h6>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="dTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Company</th>
                                    <th>Code</th>
                                    <th>Check-in Time</th>
                                    <th>Check-out Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                    <div class="text-center mt-3">
                        <button class="btn btn-outline-primary" id="load-more">Load More</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="departmentModal" tabindex="-1" aria-labelledby="departmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="departmentForm" action="{{ route('department.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id" id="department_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="departmentModalLabel">Add Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Department Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="company_id" class="form-label">Company</label>
                            <select name="company_id" id="company_id" class="form-select" required>
                                <option value="">-- Select Company --</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="code" class="form-label">Department Code</label>
                            <input type="text" name="code" id="code" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label for="start_time" class="form-label">Check-in Time</label>
                            <input type="time" name="start_time" id="start_time" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label for="end_time" class="form-label">Check Out Time</label>
                            <input type="time" name="end_time" id="end_time" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="btnSubmit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
@push('script')
<script src="{{ url('assets/vendors/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
    let lastId = null;
    let loading = false;

    function loadData(reset = false) {
        if (loading) return;
        loading = true;

        if (reset) {
            $('#dTable tbody').empty();
            lastId = null;
            $('#load-more').show();
        }

        $('#load-more').html('<div class="spinner-border spinner-border-sm"></div>');

        $.getJSON('/department/list', { last_id: lastId }, function(res) {
            let rows = '';
            $.each(res.data, function(i, user) {
                rows += `<tr>
                    <td>${user.id}</td>
                    <td>${user.name}</td>
                    <td>${user.company.name}</td>
                    <td>${user.code}</td>
                    <td>${user.start_time}</td>
                    <td>${user.end_time}</td>
                    <td>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-warning edit-data"
                                data-id="${user.id}"
                                data-name="${user.name}"
                                data-company_id="${user.company_id}"
                                data-code="${user.code}"
                                data-start_time="${user.start_time}"
                                data-end_time="${user.end_time}">
                                <i data-feather="edit-2" width="14" height="14"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-data ms-2"
                                data-id="${user.id}">
                                <i data-feather="trash-2" width="14" height="14"></i>
                            </button>
                        </div>
                    </td>
                </tr>`;
            });
            $('#dTable tbody').append(rows);
            feather.replace();

            if (res.data.length > 0) {
                lastId = res.data[res.data.length - 1].id;
            }

            if (!res.hasMore) {
                $('#load-more').hide();
            }

            $('#load-more').text('Load More');
            loading = false;
        });
    }

    // Load awal
    loadData();

    $('#load-more').click(() => loadData());

    // === Tambah Department ===
    $(document).on('click', '#btnAddDepartment', function() {
        $('#departmentForm')[0].reset();
        $('#department_id').val('');
        $('#departmentModalLabel').text('Tambah Department');
        $('#btnSubmit').text('Save');
        $('#departmentForm').attr('data-mode', 'create');
        $('#departmentModal').modal('show');
    });

    // === Edit Department ===
    $(document).on('click', '.edit-data', function() {
        const data = $(this).data();

        $('#department_id').val(data.id);
        $('#name').val(data.name);
        $('#company_id').val(data.company_id);
        $('#code').val(data.code);
        $('#start_time').val(data.start_time);
        $('#end_time').val(data.end_time);

        $('#departmentModalLabel').text('Edit Department');
        $('#btnSubmit').text('Update');
        $('#departmentForm').attr('data-mode', 'update');
        $('#departmentModal').modal('show');
    });

    // === Submit Form via AJAX ===
    $('#departmentForm').on('submit', function(e) {
        e.preventDefault();

        const mode = $(this).attr('data-mode');
        const id = $('#department_id').val();

        const formData = $(this).serialize();
        let url = '';
        let method = 'POST';

        if (mode === 'create') {
            url = '{{ route("department.store") }}';
        } else {
            url = `/department/update/${id}`;
        }

        $('#btnSubmit').prop('disabled', true).html('<div class="spinner-border spinner-border-sm"></div>');

        $.ajax({
            url: url,
            type: method,
            data: formData,
            success: function(res) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: mode === 'create'
                        ? 'Department berhasil ditambahkan.'
                        : 'Department berhasil diperbarui.',
                    timer: 1500,
                    showConfirmButton: false
                });

                $('#departmentModal').modal('hide');
                $('#btnSubmit').prop('disabled', false).text('Save');

                // reload tabel
                loadData(true);
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                let msg = 'Terjadi kesalahan.';

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    msg = Object.values(errors).map(e => e.join(', ')).join('\n');
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: msg
                });

                $('#btnSubmit').prop('disabled', false).text('Save');
            }
        });
    });

    // === Delete Data ===
    $(document).on('click', '.delete-data', function(e) {
        e.preventDefault();

        const id = $(this).data('id');
        const row = $(this).closest('tr');

        Swal.fire({
            title: 'Are you sure you want to delete??',
            text: 'Data department ini akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Cancel'
        }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/department/delete/${id}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Terhapus!',
                            text: res.message ?? 'Data berhasil dihapus.',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        row.fadeOut(400, () => $(this).remove());
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menghapus data.'
                        });
                    }
                });
            }
        });
    });
</script>

@endpush
