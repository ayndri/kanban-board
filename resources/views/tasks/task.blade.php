@extends('template.app')

@section('content')
<div id="app" data-user-role="{{ Auth::user()->role->name ?? null }}">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">Task Management Board</h4>
        <div class="d-flex align-items-center">
            <div class="page-title-right me-3">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Menu</a></li>
                    <li class="breadcrumb-item active">Tasks</li>
                </ol>
            </div>

            <div class="me-3">
                <select id="filter-by-assignee" class="form-select" style="min-width: 200px;">
                    <option value="all">Filter by Assignee...</option>
                </select>
            </div>

            @php
            // Ambil role user, fallback ke null jika tidak ada
            $userRole = Auth::user()->role->name ?? null;
            @endphp

            {{-- Tampilkan tombol ini HANYA jika role-nya 'manager' atau 'admin' --}}
            @if(in_array($userRole, ['manager', 'admin']))
            <button id="add-new-task-btn" class="btn btn-primary">
                <i class="ri-add-line align-bottom me-1"></i> Add New Task
            </button>
            @endif
        </div>
    </div>

    <div class="row" id="kanban-board-container">
    </div>

    <div class="modal fade" id="taskModal" tabindex="-1" aria-hidden="true">
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

    <div class="modal fade" id="taskDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" id="task-details-content">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Memuat SortableJS dari CDN --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->

<!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        let statuses = @json($statusesJson) || [];
        const users = @json($usersJson) || [];

        // ==============================================
        // ============ VARIABEL PERMISSION =============
        // ==============================================
        const userRoleElement = document.getElementById('app');
        let userRole = userRoleElement.dataset.userRole;
        const canManageTasks = ['manager', 'admin'].includes(userRole);
        // ==============================================

        let currentAssigneeFilter = 'all';
        const filterDropdown = document.getElementById('filter-by-assignee');

        const csrfToken = document.querySelector('meta[name="csrf-token"]') ?
            document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

        const boardContainer = document.getElementById('kanban-board-container');
        const taskModalEl = document.getElementById('taskModal');
        const taskDetailsModalEl = document.getElementById('taskDetailsModal');
        const taskModal = new bootstrap.Modal(taskModalEl);
        const taskDetailsModal = new bootstrap.Modal(taskDetailsModalEl);

        // Helper functions
        const getPriorityClass = (priority) => ({
            'High': 'danger',
            'Medium': 'warning',
            'Low': 'success'
        } [priority] || 'secondary');

        const getStatusColor = (slug) => ({
            'to-do': 'info',
            'in-progress': 'warning',
            'in-review': 'primary',
            'completed': 'success'
        } [slug] || 'light');

        const formatDate = (dateString) => dateString ? new Date(dateString).toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        }) : 'No due date';

        function findTaskById(taskId) {
            for (const status of statuses) {
                const task = status.tasks.find(t => t.id === taskId);
                if (task) {
                    return {
                        task,
                        status
                    };
                }
            }
            return {
                task: null,
                status: null
            };
        }

        // =======================================================
        // 1. UPDATE: FUNGSI RENDER BOARD (Tidak banyak berubah)
        // =======================================================
        function renderBoard() {
            boardContainer.innerHTML = '';

            statuses.forEach(status => {
                const column = document.createElement('div');
                column.className = 'col-xl-3 col-lg-4 col-md-6 mb-4';
                column.id = `status-column-${status.id}`;

                let filteredTasks;
                if (currentAssigneeFilter === 'all') {
                    filteredTasks = status.tasks;
                } else if (currentAssigneeFilter === 'unassigned') {
                    filteredTasks = status.tasks.filter(task => !task.user_id);
                } else {
                    filteredTasks = status.tasks.filter(task => task.user_id == currentAssigneeFilter);
                }

                const taskCardsHtml = filteredTasks.length > 0 ?
                    filteredTasks.map(task => createTaskCard(task)).join('') :
                    createEmptyState();

                column.innerHTML = `
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-light p-3 border-top-3 border-${getStatusColor(status.slug)}">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0 text-uppercase fw-bold">
                                ${status.title}
                                <span id="count-${status.id}" class="badge rounded-pill ms-2 bg-soft-${getStatusColor(status.slug)} text-${getStatusColor(status.slug)}">${filteredTasks.length}</span>
                            </h6>
                            ${canManageTasks ? `
                            <button class="btn btn-soft-primary btn-sm p-0 add-task-in-column-btn" style="width: 24px; height: 24px;" data-status-id="${status.id}">
                                <i class="ri-add-fill"></i>
                            </button>
                            ` : ''}
                        </div>
                    </div>
                    <div class="card-body p-2 flex-grow-1 overflow-auto tasks-list" data-status-id="${status.id}" style="min-height: 400px;">
                        ${taskCardsHtml}
                    </div>
                </div>`;
                boardContainer.appendChild(column);
            });

            initializeDragAndDrop();
            addEventListeners();
        }

        // =======================================================
        // 2. UPDATE: CARD DENGAN TOMBOL DELETE KECIL
        // =======================================================
        function createTaskCard(task) {
            const user = users.find(u => u.id === task.user_id);
            return `
            <div class="card kanban-card mb-2 shadow-sm border-start border-5 border-${getPriorityClass(task.priority)}" data-task-id="${task.id}">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="fs-15 mb-0 text-truncate" style="max-width: ${canManageTasks ? '80%' : '100%'}">${task.title}</h6>
                        <div class="d-flex gap-1">
                            <span class="badge bg-soft-${getPriorityClass(task.priority)} text-${getPriorityClass(task.priority)}">${task.priority}</span>
                            ${canManageTasks ? `
                            <button class="btn btn-sm btn-ghost-danger p-0 delete-task-btn-small" style="width:20px; height:20px; line-height:1;" data-task-id="${task.id}" title="Delete Task">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                            ` : ''}
                        </div>
                    </div>
                    <p class="text-muted small mb-3 truncate-2-lines">${task.description || ''}</p>
                    <div class="d-flex align-items-center justify-content-between">
                        <small class="text-muted"><i class="ri-calendar-todo-line me-1"></i> ${formatDate(task.due_date)}</small>
                        ${user ? `
                        <div class="avatar-group">
                            <div class="avatar-group-item">
                                <a href="javascript: void(0);" class="d-inline-block" title="${user.name}">
                                    ${user.photo ?
                                    `<img src="${user.photo}" alt="" class="rounded-circle avatar-xs">` :
                                    `<div class="avatar-xs d-flex align-items-center justify-content-center rounded-circle bg-soft-primary text-primary fw-medium">${user.name.charAt(0)}</div>`}
                                </a>
                            </div>
                        </div>` : ''}
                    </div>
                </div>
            </div>`;
        };

        function createEmptyState() {
            return `
            <div class="text-center text-muted p-4 h-100 d-flex flex-column justify-content-center align-items-center">
                <img src="https://www.gstatic.com/images/icons/material/system/2x/inbox_gm_blue_48dp.png" alt="Empty" style="width: 60px; opacity: 0.5;">
                <p class="mt-3 mb-0 fw-medium">No Tasks Here</p>
                <small>Add a new task to get started.</small>
            </div>`;
        }

        // ... (Fungsi initializeDragAndDrop SAMA SEPERTI SEBELUMNYA) ...
        function initializeDragAndDrop() {
            const lists = document.querySelectorAll('.tasks-list');
            lists.forEach(list => {
                new Sortable(list, {
                    group: 'tasks',
                    animation: 150,
                    disabled: !canManageTasks,
                    onEnd: function(evt) {
                        const taskId = parseInt(evt.item.dataset.taskId);
                        const newStatus = evt.to.dataset.statusId;
                        const oldStatus = evt.from.dataset.statusId;
                        const newIndex = evt.newIndex;

                        const orderData = [];
                        evt.to.querySelectorAll('.kanban-card').forEach((card, index) => {
                            orderData.push({
                                id: parseInt(card.dataset.taskId),
                                order: index
                            });
                        });

                        updateTaskDataLocally(taskId, newStatus, oldStatus, newIndex);
                        renderBoard(); // Re-render untuk merapikan UI

                        fetch('{{ route("tasks.reorder") }}', {
                                method: 'post',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    taskId: taskId,
                                    newStatus: newStatus,
                                    oldStatus: oldStatus,
                                    newOrder: newIndex,
                                    orderData: orderData
                                })
                            })
                            .then(response => response.json())
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire('Error', 'Failed to move task.', 'error');
                            });
                    }
                });
            });
        };

        // ... (Fungsi updateTaskDataLocally SAMA SEPERTI SEBELUMNYA) ...
        function updateTaskDataLocally(taskId, newStatusSlug, oldStatusSlug, newIndex) {
            let taskToMove;
            let oldStatus = statuses.find(s => s.id === oldStatusSlug);
            if (oldStatus) {
                const taskIndex = oldStatus.tasks.findIndex(t => t.id === taskId);
                if (taskIndex > -1) {
                    taskToMove = oldStatus.tasks[taskIndex];
                    oldStatus.tasks.splice(taskIndex, 1);
                }
            }
            if (taskToMove) {
                let newStatus = statuses.find(s => s.id === newStatusSlug);
                if (newStatus) {
                    taskToMove.status = newStatusSlug;
                    newStatus.tasks.splice(newIndex, 0, taskToMove);
                }
            }
        }

        // =======================================================
        // 3. UPDATE: EVENT LISTENER
        // =======================================================
        function addEventListeners() {
            // Klik card untuk detail
            document.querySelectorAll('.kanban-card').forEach(card => {
                card.addEventListener('click', (e) => {
                    // JANGAN buka modal jika yang diklik adalah tombol delete kecil
                    if (e.target.closest('.delete-task-btn-small')) return;

                    const taskId = parseInt(e.currentTarget.dataset.taskId);
                    showTaskDetails(taskId);
                });
            });

            // Klik tombol '+' di kolom
            document.querySelectorAll('.add-task-in-column-btn').forEach(button => {
                button.addEventListener('click', (e) => {
                    const statusId = e.currentTarget.dataset.statusId;
                    openTaskModal(null, statusId);
                });
            });

            // Klik tombol Delete kecil di Card (Event Delegation)
            document.querySelectorAll('.delete-task-btn-small').forEach(button => {
                button.addEventListener('click', (e) => {
                    e.stopPropagation(); // Mencegah klik tembus ke card
                    const taskId = e.currentTarget.dataset.taskId;
                    confirmDeleteTask(taskId);
                });
            });
        }

        // ... (Fungsi openTaskModal SAMA SEPERTI SEBELUMNYA) ...
        function openTaskModal(taskId = null, statusId = null) {
            const form = document.getElementById('task-form');
            form.reset();
            document.getElementById('task-id').value = '';
            const statusDropdown = document.getElementById('task-status');
            statusDropdown.innerHTML = statuses.map(s => `<option value="${s.id}">${s.title}</option>`).join('');
            const assigneeDropdown = document.getElementById('task-assignee');
            assigneeDropdown.innerHTML = `<option value="">Unassigned</option>` + users.map(u => `<option value="${u.id}">${u.name}</option>`).join('');
            if (taskId) {
                document.getElementById('taskModalLabel').innerText = 'Edit Task';
                const {
                    task
                } = findTaskById(taskId);
                if (task) {
                    document.getElementById('task-id').value = task.id;
                    document.getElementById('task-title').value = task.title;
                    document.getElementById('task-description').value = task.description || '';
                    document.getElementById('task-status').value = task.status;
                    document.getElementById('task-priority').value = task.priority;
                    document.getElementById('task-assignee').value = task.user_id || '';
                    if (task.due_date) {
                        document.getElementById('task-due-date').value = new Date(task.due_date).toISOString().split('T')[0];
                    }
                }
            } else {
                document.getElementById('taskModalLabel').innerText = 'Create New Task';
                if (statusId) {
                    statusDropdown.value = statusId;
                }
            }
            taskModal.show();
        }

        // =======================================================
        // 4. UPDATE: MODAL DETAIL DENGAN TOMBOL DELETE BESAR
        // =======================================================
        function showTaskDetails(taskId) {
            const {
                task,
                status
            } = findTaskById(taskId);
            if (task && status) {
                const user = users.find(u => u.id === task.user_id);
                const detailsContent = document.getElementById('task-details-content');

                detailsContent.innerHTML = `
                <div class="modal-header">
                    <h5 class="modal-title">${task.title}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Description:</strong><br>${task.description || 'No description provided.'}</p><hr>
                    <div class="row">
                        <div class="col-6"><strong>Status:</strong> <span class="badge rounded-pill bg-soft-${getStatusColor(status.slug)} text-${getStatusColor(status.slug)}">${status.title}</span></div>
                        <div class="col-6"><strong>Priority:</strong> <span class="badge bg-soft-${getPriorityClass(task.priority)} text-${getPriorityClass(task.priority)}">${task.priority}</span></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6"><strong>Assigned to:</strong> ${user ? user.name : 'Unassigned'}</div>
                        <div class="col-6"><strong>Due Date:</strong> ${formatDate(task.due_date)}</div>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    ${canManageTasks ? `
                    <button type="button" id="delete-task-btn-modal" class="btn btn-outline-danger">
                        <i class="ri-delete-bin-line me-1"></i> Delete
                    </button>
                    ` : '<div></div>'}

                    <div>
                        <button type="button" class="btn btn-light me-1" data-bs-dismiss="modal">Close</button>
                        ${canManageTasks ? `
                        <button type="button" id="edit-task-btn" class="btn btn-primary">Edit Task</button>
                        ` : ''}
                    </div>
                </div>
                `;

                taskDetailsModal.show();

                if (canManageTasks) {
                    // Tombol Edit
                    document.getElementById('edit-task-btn').addEventListener('click', () => {
                        taskDetailsModal.hide();
                        openTaskModal(task.id);
                    });
                    // Tombol Delete
                    document.getElementById('delete-task-btn-modal').addEventListener('click', () => {
                        taskDetailsModal.hide(); // Tutup modal detail dulu
                        confirmDeleteTask(task.id);
                    });
                }
            }
        }

        // =======================================================
        // 5. BARU: FUNGSI DELETE TASK
        // =======================================================
        function confirmDeleteTask(taskId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteTask(taskId);
                }
            });
        }

        function deleteTask(taskId) {
            fetch(`{{ url('tasks') }}/${taskId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Hapus task dari array lokal 'statuses'
                    for (const status of statuses) {
                        const index = status.tasks.findIndex(t => t.id == taskId);
                        if (index > -1) {
                            status.tasks.splice(index, 1);
                            break;
                        }
                    }

                    renderBoard(); // Update tampilan tanpa reload

                    Swal.fire(
                        'Deleted!',
                        'Your task has been deleted.',
                        'success'
                    );
                })
                .catch(error => {
                    console.error('Error deleting task:', error);
                    Swal.fire('Error', 'Failed to delete task.', 'error');
                });
        }

        // Tombol "Add New Task" utama
        const mainAddTaskBtn = document.getElementById('add-new-task-btn');
        if (mainAddTaskBtn) {
            mainAddTaskBtn.addEventListener('click', () => openTaskModal());
        }

        // ... (Fungsi submit form SAMA SEPERTI SEBELUMNYA) ...
        document.getElementById('task-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const taskId = document.getElementById('task-id').value;
            const isEdit = taskId !== '';
            const formData = {
                title: document.getElementById('task-title').value,
                description: document.getElementById('task-description').value,
                priority: document.getElementById('task-priority').value,
                due_date: document.getElementById('task-due-date').value,
                user_id: parseInt(document.getElementById('task-assignee').value) || null,
                status: document.getElementById('task-status').value
            };
            let url = isEdit ? `{{ url('tasks') }}/${taskId}` : '{{ route("tasks.store") }}';
            let method = isEdit ? 'PATCH' : 'POST';
            fetch(url, {
                    method: method,
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
                .then(savedTask => {
                    if (isEdit) {
                        let taskIndex = -1;
                        for (const status of statuses) {
                            taskIndex = status.tasks.findIndex(t => t.id === savedTask.id);
                            if (taskIndex > -1) {
                                status.tasks.splice(taskIndex, 1);
                                break;
                            }
                        }
                    }
                    const targetStatus = statuses.find(s => s.id === savedTask.status);
                    if (targetStatus) {
                        targetStatus.tasks.push(savedTask);
                    }
                    renderBoard();
                    taskModal.hide();
                    Swal.fire(
                        'Success!',
                        `Task has been ${isEdit ? 'updated' : 'created'}.`,
                        'success'
                    );
                })
                .catch(error => {
                    console.error('Error saving task:', error);
                    if (error.errors) {
                        let errorMsg = Object.values(error.errors).map(e => e[0]).join('<br>');
                        Swal.fire('Validation Error', errorMsg, 'error');
                    } else {
                        Swal.fire('Error', 'Failed to save task.', 'error');
                    }
                });
        });

        function populateFilterDropdown() {
            filterDropdown.innerHTML = `<option value="all">All Assignees</option>`;
            users.forEach(user => {
                filterDropdown.innerHTML += `<option value="${user.id}">${user.name}</option>`;
            });
            filterDropdown.innerHTML += `<option value="unassigned">Unassigned</option>`;
        }
        filterDropdown.addEventListener('change', (e) => {
            currentAssigneeFilter = e.target.value;
            renderBoard();
        });

        populateFilterDropdown();
        renderBoard();
    });
</script>
<style>
    .kanban-card {
        transition: all 0.2s ease-in-out;
        cursor: pointer;
        border-left: 3px solid transparent;
    }

    /* Tambahkan style untuk card yang tidak bisa di-drag */
    .tasks-list:not(.sortable-enabled) .kanban-card {
        cursor: default;
    }

    .kanban-card:hover {

        /* Nonaktifkan hover effect jika tidak bisa me-manage */
        transform: $ {
            @json(in_array(Auth::user()->role->name ?? null, ['manager', 'admin'])) ? 'translateY(-3px)' : 'none'
        }

        ;

        box-shadow: $ {
            @json(in_array(Auth::user()->role->name ?? null, ['manager', 'admin'])) ? '0 4px 12px rgba(0, 0, 0, .1) !important' : 'none'
        }

        ;
    }

    .truncate-2-lines {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .border-top-3 {
        border-top-width: 3px !important;
    }

    .border-start-5 {
        border-left-width: 5px !important;
    }

    .tasks-list {
        overflow-y: auto;
    }

    html,
    body {
        overflow-x: hidden !important;
    }
</style>
@endpush