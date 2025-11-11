@extends('template.app')

@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Roles</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Menu</a></li>
            <li class="breadcrumb-item active">Roles</li>
        </ol>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Role Data</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal-Create">
            <i class="ri-add-line align-bottom me-1"></i> New Role
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="myTable" class="table table-hover align-middle nowrap" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>Name (ID)</th>
                        <th>Label (Display Name)</th>
                        <th style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $role)
                    <tr>
                        <td><span class="badge bg-soft-secondary text-secondary">{{ $role->name }}</span></td>
                        <td>{{ $role->label ?? 'N/A' }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm btn-outline-primary btn-edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#Modal-Edit"
                                    data-id="{{ $role->id }}"
                                    data-name="{{ $role->name }}"
                                    data-label="{{ $role->label }}"
                                    title="Edit">
                                    <i class="ri-pencil-line"></i>
                                </button>

                                <button class="btn btn-sm btn-outline-danger btn-delete"
                                    data-id="{{ $role->id }}"
                                    data-name="{{ $role->label ?? $role->name }}"
                                    title="Delete">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">No roles found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<form id="delete-form" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>


{{-- ================= MODALS ================= --}}

<div class="modal fade" id="Modal-Create" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Create New Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_name" class="form-label">Role Name (ID)</label>
                        <input type="text" name="name" class="form-control" id="create_name" placeholder="e.g., admin_finance (no spaces)" required>
                        <div class="form-text">This is the unique ID, e.g., 'admin', 'user', 'finance_manager'.</div>
                    </div>
                    <div class="mb-3">
                        <label for="create_label" class="form-label">Label (Display Name)</label>
                        <input type="text" name="label" class="form-control" id="create_label" placeholder="e.g., Admin Finance">
                        <div class="form-text">This is the friendly name shown in tables.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="Modal-Edit" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Role Name (ID)</label>
                        <input type="text" name="name" class="form-control" id="edit_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_label" class="form-label">Label (Display Name)</label>
                        <input type="text" name="label" class="form-control" id="edit_label">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Role</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Memuat SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- Memuat DataTables (jQuery harus dimuat SEBELUM DataTables.js) --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

<script>
    $(document).ready(function() {

        // 1. Inisialisasi DataTables
        if ($.fn.DataTable.isDataTable('#myTable')) {
            $('#myTable').DataTable().destroy();
        }
        $('#myTable').DataTable({
            "order": [],
            "columnDefs": [{
                "orderable": false,
                "targets": 2 // Kolom 'Actions'
            }]
        });

        // 2. Notifikasi Toast (jika ada)
        @if(session('success'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: '{{ session("success") }}',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
        @endif

        @if(session('error'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: '{{ session("error") }}',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true
        });
        @endif

        // 3. Event Listener untuk Modal Edit
        $('#Modal-Edit').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const id = button.data('id');
            const name = button.data('name');
            const label = button.data('label'); // Ganti dari description ke label

            const form = $('#editForm');
            // Set action URL form
            form.attr('action', `/roles/${id}`); // Ganti ke /roles/

            const modal = $(this);
            modal.find('#edit_name').val(name);
            modal.find('#edit_label').val(label); // Ganti ke edit_label
        });

        // 4. Event Listener untuk Tombol Delete
        $('#myTable tbody').on('click', '.btn-delete', function(e) {
            e.preventDefault();
            const button = $(this);
            const roleId = button.data('id'); // ganti ke roleId
            const roleName = button.data('name'); // ganti ke roleName

            const form = $('#delete-form');
            form.attr('action', `/roles/${roleId}`); // ganti ke /roles/

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete ${roleName}. This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

    });
</script>
@endpush