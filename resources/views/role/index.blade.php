@extends('layout.admin.app')
@push('style')
<link rel="stylesheet" href="{{ url("assets/vendors/sweetalert2/sweetalert2.min.css") }}">
@endpush

@section('content')
<div class="page-content">
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Role Management</h4>
        </div>
        <div>
            <button class="btn btn-primary" id="btn-add">Tambah Role</button>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="dTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Permissions</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        {{-- <button class="btn btn-outline-primary" id="load-more">Load More</button> --}}
                        <button class="btn btn-primary" id="load-more">Load More</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add/Edit -->
<div class="modal fade" id="roleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="roleForm">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="role_id">
                    <div class="mb-3">
                        <label>Nama Role</label>
                        <input type="text" class="form-control" id="role_name" required>
                    </div>
                    <div class="mb-3">
                        <label>Permissions</label>
                        <div class="row">
                            @foreach($permissions as $perm)
                                <div class="col-md-4">
                                    <label>
                                        <input type="checkbox" class="perm-checkbox" value="{{ $perm->name }}"> {{ $perm->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btn-save">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ url("assets/vendors/sweetalert2/sweetalert2.min.js") }}"></script>

<script>
let lastId = null;
let loading = false;
let editMode = false;

function loadData() {
    if (loading) return;
    loading = true;
    $('#load-more').html('<div class="loader"></div>');

    $.getJSON('/role/list', { last_id: lastId }, function(res) {
        let rows = '';
        $.each(res.data, function(i, role) {
            let perms = role.permissions.length
                ? role.permissions.map(p => `<span class="badge bg-success me-1">${p.name}</span>`).join(' ')
                : '-';

            rows += `<tr>
                <td>${role.id}</td>
                <td>${role.name}</td>
                <td>${perms}</td>
                <td>
                    <button class="btn btn-sm btn-warning btn-edit" data-id="${role.id}">
                        <i data-feather="edit-2" width="14" height="14"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-delete" data-id="${role.id}">
                        <i data-feather="trash-2" width="14" height="14"></i>
                    </button>
                </td>
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

// open modal tambah
$('#btn-add').click(function() {
    editMode = false;
    $('#roleModal .modal-title').text('Tambah Role');
    $('#roleForm')[0].reset();
    $('#role_id').val('');
    $('.perm-checkbox').prop('checked', false);
    $('#roleModal').modal('show');
});

// simpan data
$('#roleForm').submit(function(e) {
    e.preventDefault();
    showLoader();
    $('#btn-save').prop('disabled', true);
    let id = $('#role_id').val();
    let url = id ? '/role/' + id : '/role';
    let method = id ? 'PUT' : 'POST';

    let perms = [];
    $('.perm-checkbox:checked').each(function() {
        perms.push($(this).val());
    });

    $.ajax({
        url: url,
        type: method,
        data: {
            name: $('#role_name').val(),
            permissions: perms,
            _token: '{{ csrf_token() }}'
        },
        success: function(res) {
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: res.message
            });
            $('#roleModal').modal('hide');
            $('#dTable tbody').html('');
            lastId = null;
            $('#load-more').show();
            $('#btn-save').prop('disabled', false);
            loadData();
            hideLoader();
        },
        error: function(xhr) {
            hideLoader();
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat menyimpan perubahan.'
            });
        }
    });
});

// edit data
$(document).on('click', '.btn-edit', function() {
    const this_button = $(this);
    let id = this_button.data('id');
    $('.btn-edit').prop('disabled', true);
    showLoader();
    $.ajax({
        url: '/role/' + id,
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            editMode = true;
            $('#roleModal .modal-title').text('Edit Role');
            $('#role_id').val(res.id);
            $('#role_name').val(res.name);
            $('.perm-checkbox').prop('checked', false);

            if (res.permissions && res.permissions.length > 0) {
                res.permissions.forEach(function(p) {
                    $('.perm-checkbox[value="' + p.name + '"]').prop('checked', true);
                });
            }

            $('#roleModal').modal('show');
            $('.btn-edit').prop('disabled', false);
            hideLoader();
        },
        error: function(xhr, status, error) {
            console.error('Gagal memuat data role:', error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat menyimpan perubahan.'
            });
            hideLoader();
        }
    });

});

// delete data
$(document).on('click', '.btn-delete', function() {
    let id = $(this).data('id');
    Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Data ini akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoader();
            $.ajax({
                url: '/role/' + id,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses!',
                        text: res.message
                    });
                    $('#dTable tbody').html('');
                    lastId = null;
                    $('#load-more').show();
                    loadData();
                    hideLoader();
                }
            });
        }
    });
});

$('#load-more').click(loadData);
loadData();
</script>
@endpush
