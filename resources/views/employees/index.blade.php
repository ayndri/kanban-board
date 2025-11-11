@extends('template.app')

@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Employees</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Menu</a></li>
            <li class="breadcrumb-item active">Employees</li>
        </ol>
    </div>
</div>


<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Employee Data</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal-Create">
            <i class="ri-user-add-line align-bottom me-1"></i> New Employee
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            {{-- Mengganti tabel bordered dengan desain yang lebih bersih --}}
            <table id="myTable" class="table table-hover align-middle nowrap" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>Employee</th>
                        <th>Title / Division</th>
                        <th>Contact</th>
                        <th>Role</th>
                        <th style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                    <tr>
                        {{-- KOLOM 1: EMPLOYEE (Foto & Nama) --}}
                        <td>
                            <div class="d-flex align-items-center">
                                {{-- Logika Avatar (Foto atau Inisial) --}}
                                @if ($user->photo)
                                <img src="{{ asset('storage/' . $user->photo) }}" alt="" class="avatar-xs rounded-circle me-2">
                                @else
                                <div class="avatar-xs d-flex align-items-center justify-content-center rounded-circle bg-soft-primary text-primary me-2">
                                    <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                                @endif
                                <span class="fw-medium">{{ $user->name }}</span>
                            </div>
                        </td>

                        {{-- KOLOM 2: TITLE / DIVISION --}}
                        <td>
                            {{-- Mengambil data dari relasi 'employee' --}}
                            <div class="d-flex flex-column">
                                <span class="text-dark">{{ $user->employee?->job_title ?? 'N/A' }}</span>
                                <small class="text-muted">{{ $user->employee?->division?->name ?? 'No Division' }}</small>
                            </div>
                        </td>

                        {{-- KOLOM 3: CONTACT (Email & Telepon) --}}
                        <td>
                            <div class="d-flex flex-column">
                                <span>{{ $user->email }}</span>
                                <small class="text-muted">{{ $user->employee?->phone ?? 'No Phone' }}</small>
                            </div>
                        </td>

                        {{-- KOLOM 4: ROLE --}}
                        <td>
                            {{-- Mengambil nama role dari relasi 'role' --}}
                            <span class="badge bg-soft-primary text-primary">{{ $user->role?->label ?? 'N/A' }}</span>
                        </td>

                        {{-- KOLOM 5: ACTIONS --}}
                        <td>
                            <div class="d-flex gap-1">
                                {{-- Tombol Edit: Menyimpan SEMUA data di data-attributes --}}
                                <button class="btn btn-sm btn-outline-primary btn-edit-employee"
                                    data-bs-toggle="modal"
                                    data-bs-target="#Modal-Edit"
                                    data-user-id="{{ $user->id }}"
                                    data-user-name="{{ $user->name }}"
                                    data-user-email="{{ $user->email }}"
                                    data-user-role-id="{{ $user->role_id }}"
                                    data-employee-job-title="{{ $user->employee?->job_title }}"
                                    data-employee-phone="{{ $user->employee?->phone }}"
                                    data-employee-division-id="{{ $user->employee?->division_id }}"
                                    title="Edit">
                                    <i class="ri-pencil-line"></i>
                                </button>

                                {{-- Tombol Assignment (Logika Anda sudah benar) --}}
                                <button class="btn btn-sm btn-outline-success btn-add-assignment"
                                    data-bs-toggle="modal"
                                    data-bs-target="#Modal-Assignment"
                                    data-user-id="{{ $user->id }}"
                                    data-user-name="{{ $user->name }}"
                                    title="Add Task">
                                    <i class="ri-task-line"></i>
                                </button>

                                {{-- Tombol Delete (Dihandle oleh SweetAlert) --}}
                                <button class="btn btn-sm btn-outline-danger btn-delete-employee"
                                    data-user-id="{{ $user->id }}"
                                    data-user-name="{{ $user->name }}"
                                    title="Delete">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No employees found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Form Delete (Tersembunyi) --}}
{{-- Ini akan di-trigger oleh SweetAlert --}}
<form id="delete-employee-form" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>


{{--
    ===================================================================
    MODALS (Sudah disesuaikan dengan Skema Database baru)
    ===================================================================
    --}}

<div class="modal fade" id="Modal-Create" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Create New Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- Ganti route ke 'employees.store' --}}
            <form action="{{ route('employees.store') }}" method="POST" id="create-employee-form">
                @csrf
                <div class="modal-body">
                    <h6 class="fs-15">Login Details (Tabel Users)</h6>
                    <hr class="mt-1">
                    <div class="mb-3">
                        <label for="create_name" class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" id="create_name" placeholder="Enter Full Name" required>
                    </div>
                    <div class="mb-3">
                        <label for="create_email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="create_email" placeholder="Enter email" required>
                    </div>
                    <div class="mb-3">
                        <label for="create_role_id" class="form-label">Role</label>
                        <select name="role_id" id="create_role_id" class="form-select" required>
                            <option value="">Select Role...</option>
                            @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="create_password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="create_password" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="create_password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" id="create_password_confirmation" required>
                        </div>
                    </div>

                    <h6 class="fs-15 mt-3">Profile Details (Tabel Employees)</h6>
                    <hr class="mt-1">
                    <div class="mb-3">
                        <label for="create_division_id" class="form-label">Division</label>
                        <select name="division_id" id="create_division_id" class="form-select" required>
                            <option value="">Select Division...</option>
                            @foreach ($divisions as $division)
                            <option value="{{ $division->id }}">{{ $division->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="create_job_title" class="form-label">Job Title</label>
                        <input type="text" name="job_title" class="form-control" id="create_job_title" placeholder="e.g., Software Engineer">
                    </div>
                    <div class="mb-3">
                        <label for="create_phone" class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" id="create_phone" placeholder="e.g., 08123...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Employee</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="Modal-Edit" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- Form action akan di-set oleh JavaScript --}}
            <form action="" method="POST" id="editEmployeeForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_user_id" name="user_id">

                    <h6 class="fs-15">Login Details (Tabel Users)</h6>
                    <hr class="mt-1">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" id="edit_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="edit_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_role_id" class="form-label">Role</label>
                        <select name="role_id" id="edit_role_id" class="form-select" required>
                            @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <h6 class="fs-15 mt-3">Profile Details (Tabel Employees)</h6>
                    <hr class="mt-1">
                    <div class="mb-3">
                        <label for="edit_division_id" class="form-label">Division</label>
                        <select name="division_id" id="edit_division_id" class="form-select" required>
                            @foreach ($divisions as $division)
                            <option value="{{ $division->id }}">{{ $division->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_job_title" class="form-label">Job Title</label>
                        <input type="text" name="job_title" class="form-control" id="edit_job_title">
                    </div>
                    <div class="mb-3">
                        <label for="edit_phone" class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" id="edit_phone">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Employee</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="Modal-Assignment" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskModalLabel">Create New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="task-form">
                    <input type="hidden" id="task-id">
                    <div class="mb-3">
                        <label class="form-label">Task Title</label>
                        <input type="text" id="task-title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea id="task-description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select id="task-status" class="form-select"></select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Priority</label>
                            <select id="task-priority" class="form-select">
                                <option>Low</option>
                                <option>Medium</option>
                                <option>High</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Assign to</label>
                        <select id="task-assignee" class="form-select"></select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Due Date</label>
                        <input type="date" id="task-due-date" class="form-control">
                    </div>
                    <div class="modal-footer pb-0 px-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Task</button>
                    </div>
                </form>
            </div>
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

{{-- 1. Inisialisasi variabel JavaScript dengan data dari Controller --}}
<script>
    // Variabel ini diambil dari controller
    const allStatuses = @json($statuses ?? []);
    const allUsers = @json($allUsersForDropdown ?? []);
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
</script>

<script>
    $(document).ready(function() {

        if ($.fn.DataTable.isDataTable('#myTable')) {
            $('#myTable').DataTable().destroy();
        }

        $('#myTable').DataTable({
            "order": [],
            "columnDefs": [{
                "orderable": false,
                "targets": 4
            }]
        });

        // 3. Notifikasi Toast SweetAlert
        @if(session('success'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: '{{ session("success") }}', // Perbaiki typo string
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
        @endif

        // 4. Event Listener untuk Modal Edit (Ini sudah benar)
        $('#Modal-Edit').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const userId = button.data('user-id');
            const name = button.data('user-name');
            const email = button.data('user-email');
            const roleId = button.data('user-role-id');
            const jobTitle = button.data('employee-job-title');
            const phone = button.data('employee-phone');
            const divisionId = button.data('employee-division-id');
            const form = $('#editEmployeeForm');
            form.attr('action', `/employees/${userId}`);
            const modal = $(this);
            modal.find('#edit_user_id').val(userId);
            modal.find('#edit_name').val(name);
            modal.find('#edit_email').val(email);
            modal.find('#edit_role_id').val(roleId);
            modal.find('#edit_job_title').val(jobTitle);
            modal.find('#edit_phone').val(phone);
            modal.find('#edit_division_id').val(divisionId);
        });

        // 5. PERBAIKAN: Event Listener untuk Modal Assignment (Add Task)
        $('#Modal-Assignment').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const employeeId = button.data('user-id'); // ID user yang diklik
            const modal = $(this);

            // Populate Status Dropdown
            const statusDropdown = modal.find('#task-status');
            statusDropdown.html(''); // Kosongkan dulu
            allStatuses.forEach(s => {
                statusDropdown.append(new Option(s.title, s.id));
            });
            statusDropdown.val('to-do'); // Set default ke 'to-do'

            // Populate Assignee Dropdown
            const assigneeDropdown = modal.find('#task-assignee');
            assigneeDropdown.html(''); // Kosongkan dulu
            assigneeDropdown.append(new Option('Unassigned', ''));
            allUsers.forEach(u => {
                assigneeDropdown.append(new Option(u.name, u.id));
            });

            // *** Otomatis pilih user yang diklik ***
            assigneeDropdown.val(employeeId);
        });

        // 6. Event Listener untuk Tombol Delete (Ini sudah benar)
        $('#myTable tbody').on('click', '.btn-delete-employee', function(e) {
            e.preventDefault();
            const button = $(this);
            const userId = button.data('user-id');
            const userName = button.data('user-name');
            const form = $('#delete-employee-form');
            form.attr('action', `/employees/${userId}`);
            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete ${userName}. This action cannot be undone.`,
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

        // 7. PERBAIKAN: AJAX Submit untuk form Task
        // Target ID form yang benar: '#task-form'
        $('#task-form').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const url = '{{ route("tasks.store") }}';

            // Ambil data dari ID (sesuai modal Kanban)
            const formData = {
                user_id: $('#task-assignee').val() ? parseInt($('#task-assignee').val()) : null,
                title: $('#task-title').val(),
                description: $('#task-description').val(),
                due_date: $('#task-due-date').val(),
                priority: $('#task-priority').val(), // Perbaiki typo 'nameD'
                status: $('#task-status').val(),
            };

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw err;
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    $('#Modal-Assignment').modal('hide');
                    form[0].reset();

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Task created successfully!',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                })
                .catch(error => {
                    console.error('Error creating task:', error);
                    let errorMsg = 'Failed to create task.';
                    if (error.errors) {
                        errorMsg = Object.values(error.errors).map(e => e[0]).join('<br>');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        html: errorMsg
                    });
                });
        });

        $('#create-employee-form').on('submit', function(e) {
            e.preventDefault(); // Hentikan reload halaman

            const form = $(this);
            const url = form.attr('action');
            const formData = new FormData(this); // Gunakan FormData untuk kirim form

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json', // Minta balasan JSON
                    },
                    body: formData // Kirim data form
                })
                .then(response => {
                    // Cek jika ada error (validasi atau server)
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw err;
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    // SUKSES!
                    $('#Modal-Create').modal('hide'); // Tutup modal
                    form[0].reset(); // Kosongkan form

                    // Tampilkan SweetAlert TENGAH seperti yang Anda minta
                    Swal.fire({
                        title: 'Success!',
                        text: data.message, // Ambil pesan dari controller
                        icon: 'success'
                    }).then((result) => {
                        // Setelah user klik "OK", reload halaman untuk menampilkan
                        // user baru di tabel DataTables
                        location.reload();
                    });
                })
                .catch(error => {
                    // TANGANI ERROR
                    console.error('Error creating employee:', error);
                    let errorMsg = 'Failed to create employee.';

                    // Tampilkan error validasi dari Laravel
                    if (error.errors) {
                        errorMsg = Object.values(error.errors).map(e => e[0]).join('<br>');
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        html: errorMsg
                    });
                });
        });

    });
</script>
@endpush