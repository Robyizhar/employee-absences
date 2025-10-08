@extends('layout.admin.app')
@push('style')
<link rel="stylesheet" href="{{ url("assets/vendors/sweetalert2/sweetalert2.min.css") }}">
@endpush
@section('content')
<div class="page-content">
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">User Management</h4>
        </div>
        <div>
            <button class="btn btn-primary" id="btn-add">+ Add User</button>
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
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Company</th>
                                    <th>Role</th>
                                    <th>Action</th>
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

{{-- Modal Form --}}
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="userForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="user_id">
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" id="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" id="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" id="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Company</label>
                        <select id="company_id" class="form-select">
                            <option value="">-- Select Company --</option>
                            @foreach($companies as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Role</label>
                        <select id="role_id" class="form-select" required>
                            @foreach($roles as $r)
                                <option value="{{ $r->id }}">{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btn-save">Save</button>
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
    if (loading) return;
    loading = true;
    $('#load-more').html('<div class="loader"></div>');
    $('#load-more').show();
    $.getJSON('/user/list', { last_id: lastId }, function(res) {
        let rows = '';
        $.each(res.data, function(i, user) {
            rows += `<tr>
                <td>${user.id}</td>
                <td>${user.username}</td>
                <td>${user.email}</td>
                <td>${user.company ? user.company.name : '-'}</td>
                <td>${user.roles.length > 0 ? user.roles[0].name : '-'}</td>
                <td>
                    <button class="btn btn-sm btn-warning btn-edit" data-id="${user.id}">
                        <i data-feather="edit-2" width="14" height="14"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-delete" data-id="${user.id}">
                        <i data-feather="trash-2" width="14" height="14"></i>
                    </button>
                </td>
            </tr>`;
        });
        $('#dTable tbody').append(rows);
        feather.replace();

        if (res.data.length > 0) lastId = res.data[res.data.length - 1].id;
        if (!res.hasMore) $('#load-more').hide();

        $('#load-more').text('Load More');
        loading = false;
    });
}

$('#load-more').click(loadData);
loadData();

// Create
$('#btn-add').click(function() {
    $('#modalTitle').text('Add User');
    $('#userForm')[0].reset();
    $('#user_id').val('');
    $('#userModal').modal('show');
});

// Edit
$(document).on('click', '.btn-edit', function() {
    let id = $(this).data('id');
    showLoader();

    $.getJSON('/user/list', function(res) {
        let user = res.data.find(u => u.id == id);
        if (!user) return;

        $('#modalTitle').text('Edit User');
        $('#user_id').val(user.id);
        $('#username').val(user.username);
        $('#email').val(user.email);
        $('#company_id').val(user.company ? user.company.id : '');
        $('#role_id').val(user.roles.length ? user.roles[0].id : '');
        $('#userModal').modal('show');

        // loader disembunyikan setelah modal muncul
        hideLoader();
    })
    .fail(function() {
        alert('Gagal memuat data user');
        hideLoader(); // pastikan loader disembunyikan meskipun error
    });
});

// Save (Create or Update)
$('#userForm').submit(function(e) {
    e.preventDefault();
    showLoader();
    const id = $('#user_id').val();
    const url = id ? `/user/update/${id}` : '/user/store';
    const method = 'POST';

    $.ajax({
        url: url,
        method: method,
        data: {
            username: $('#username').val(),
            email: $('#email').val(),
            password: $('#password').val(),
            company_id: $('#company_id').val(),
            role_id: $('#role_id').val(),
            _token: '{{ csrf_token() }}'
        },
        success: function(res) {
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: res.message
            });
            $('#userModal').modal('hide');
            $('#dTable tbody').empty();
            lastId = null;
            loadData();
            hideLoader();
        },
        error: function(xhr) {
            hideLoader();
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'An error occurred while saving changes.'
            });
        }
    });
});

// Delete
$(document).on('click', '.btn-delete', function() {
    const id = $(this).data('id');
    Swal.fire({
        title: 'Are you sure you want to delete??',
        text: "This data will be permanently deleted!",
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
                url: `/user/delete/${id}`,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses!',
                        text: res.message
                    });
                    $('#dTable tbody').empty();
                    lastId = null;
                    loadData();
                    hideLoader();
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'An error occurred while saving changes.'
                    });
                    hideLoader();
                }
            });
        }
    });

});
</script>
@endpush
