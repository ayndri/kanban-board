@extends('template.app')

@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Divisions</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Menu</a></li>
            <li class="breadcrumb-item active">Divisions</li>
        </ol>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Division Data</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal-Create">
            <i class="ri-add-line align-bottom me-1"></i> New Division
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="myTable" class="table table-hover align-middle nowrap" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($divisions as $division)
                    <tr>
                        <td>{{ $division->name }}</td>
                        <td>{{ $division->description ?? 'N/A' }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm btn-outline-primary btn-edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#Modal-Edit"
                                    data-id="{{ $division->id }}"
                                    data-name="{{ $division->name }}"
                                    data-description="{{ $division->description }}"
                                    title="Edit">
                                    <i class="ri-pencil-line"></i>
                                </button>

                                <button class="btn btn-sm btn-outline-danger btn-delete"
                                    data-id="{{ $division->id }}"
                                    data-name="{{ $division->name }}"
                                    title="Delete">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">No divisions found.</td>
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
                <h5 class="modal-title" id="createModalLabel">Create New Division</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('divisions.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_name" class="form-label">Division Name</label>
                        <input type="text" name="name" class="form-control" id="create_name" placeholder="Enter Division Name" required>
                    </div>
                    <div class="mb-3">
                        <label for="create_description" class="form-label">Description (Optional)</label>
                        <textarea name="description" class="form-control" id="create_description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Division</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="Modal-Edit" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Division</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Division Name</label>
                        <input type="text" name="name" class="form-control" id="edit_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description (Optional)</label>
                        <textarea name="description" class="form-control" id="edit_description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Division</button>
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
            const description = button.data('description');

            const form = $('#editForm');
            // Set action URL form
            form.attr('action', `/divisions/${id}`);

            const modal = $(this);
            modal.find('#edit_name').val(name);
            modal.find('#edit_description').val(description);
        });

        // 4. Event Listener untuk Tombol Delete
        $('#myTable tbody').on('click', '.btn-delete', function(e) {
            e.preventDefault();
            const button = $(this);
            const divisionId = button.data('id');
            const divisionName = button.data('name');

            const form = $('#delete-form');
            form.attr('action', `/divisions/${divisionId}`);

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete ${divisionName}. This action cannot be undone.`,
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