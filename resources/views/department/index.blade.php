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
                <i class="btn-icon-prepend" data-feather="plus"></i> Tambah Department
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">Daftar Department</h6>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="dTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Perusahaan</th>
                                    <th>Kode</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @foreach(App\Models\Department::with('company')->get() as $dept)
                                    <tr>
                                        <td>{{ $dept->id }}</td>
                                        <td>{{ $dept->name }}</td>
                                        <td>{{ $dept->company->name ?? '-' }}</td>
                                        <td>{{ $dept->code ?? '-' }}</td>
                                        <td>{{ $dept->start_time }}</td>
                                        <td>{{ $dept->end_time }}</td>
                                        <td>
                                            <form action="{{ route('department.destroy', $dept->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                                    <i data-feather="trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach --}}
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
                    <h5 class="modal-title" id="departmentModalLabel">Tambah Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama Department</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="company_id" class="form-label">Perusahaan</label>
                            <select name="company_id" id="company_id" class="form-select" required>
                                <option value="">-- Pilih Perusahaan --</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="code" class="form-label">Kode Department</label>
                            <input type="text" name="code" id="code" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label for="start_time" class="form-label">Jam Masuk</label>
                            <input type="time" name="start_time" id="start_time" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label for="end_time" class="form-label">Jam Keluar</label>
                            <input type="time" name="end_time" id="end_time" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="btnSubmit" class="btn btn-primary">Simpan</button>
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

    function loadData() {
        $('#load-more').html('<div class="loader"></div>');

        if (loading) return;
        loading = true;

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
                            <button class="btn btn-sm btn-warning btn-edit edit-data"
                                data-id="${user.id}"
                                data-name="${user.name}"
                                data-company_id="${user.company_id}"
                                data-code="${user.code}"
                                data-start_time="${user.start_time}"
                                data-end_time="${user.end_time}">
                                <i data-feather="edit" width="10" height="10"></i>
                            </button>
                            <button class="btn btn-sm btn-danger btn-delete delete-data ml-2"
                                data-id="${user.id}">
                                <i data-feather="delete" width="10" height="10"></i>
                            </button>
                        </div>
                    </td>
                </tr>`;
            });
            $('#dTable tbody').append(rows);

            feather.replace();

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
        loadData();
    });

    // load awal
    loadData();

    // === Mode Tambah ===
    $(document).on('click', '#btnAddDepartment', function() {
        // reset form
        $('#departmentForm')[0].reset();
        $('#department_id').val('');

        // ubah title & action
        $('#departmentModalLabel').text('Tambah Department');
        $('#btnSubmit').text('Simpan');

        // ubah action ke store
        $('#departmentForm').attr('action', '{{ route("department.store") }}');
        $('#departmentForm').attr('method', 'POST');

        $('#departmentModal').modal('show');
    });


    // === Mode Edit ===
    $(document).on('click', '.edit-data', function() {
        const data = $(this).data();

        // isi form dengan data lama
        $('#department_id').val(data.id);
        $('#name').val(data.name);
        $('#company_id').val(data.company_id);
        $('#code').val(data.code);
        $('#start_time').val(data.start_time);
        $('#end_time').val(data.end_time);

        // ubah title & action
        $('#departmentModalLabel').text('Edit Department');
        $('#btnSubmit').text('Update');

        // ganti action ke update route
        let updateUrl = `/department/update/${data.id}`;
        $('#departmentForm').attr('action', updateUrl);
        $('#departmentForm').attr('method', 'POST');

        // $('#company_id').attr(data.company_id);


        $('#departmentModal').modal('show');
    });

    $(document).on('click', '.delete-data', function(e) {
        e.preventDefault();

        const id = $(this).data('id');
        const row = $(this).closest('tr'); // untuk hapus baris dari tabel

        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data department ini akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/department/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            });

                            // hapus baris dari tabel tanpa reload
                            row.fadeOut(400, function() {
                                $(this).remove();
                            });
                        }
                    },
                    error: function(xhr) {
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
