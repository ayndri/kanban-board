@extends('template.app')

@section('content')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Kanban Board</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Menu</a></li>
            <li class="breadcrumb-item active">Kanban Board</li>
        </ol>
    </div>
</div>


<div class="container-fluid py-4">
    <div id="app" class="d-flex flex-row flex-nowrap overflow-x-auto gap-3 pb-3" style="height: 70vh;">

        <div v-for="status in statuses" :key="status.slug" class="col-4 flex-shrink-0"
            :data-status-slug="status.slug" style="min-width: 300px;">
            <div class="bg-white rounded shadow-sm p-2 d-flex flex-column h-100">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="text-center mb-0 text-uppercase fw-bold">
                        @{{ status.title }}
                        <span class="badge" :class="getBadgeClass(status.slug)">@{{ status.tasks.length }}</span>
                    </h6>
                    <button class="btn btn-sm btn-outline-secondary py-0">
                        Add Task
                    </button>
                </div>


                <div class="overflow-auto flex-grow-1 p-1" :data-status-slug="status.slug">
                    <draggable v-model="status.tasks" group="tasks" item-key="id" class="list-group-flush"
                        @end="handleTaskMoved">
                        <template #item="{element}">
                            <div :data-id="element.id"
                                class="card mb-2 shadow-sm cursor-grab">
                                <div class="card-body p-3">
                                    <h6 class="fs-6 mb-2 text-truncate">
                                        <a href="#">@{{ element.title }}</a>
                                    </h6>
                                    <p class="text-muted small mb-2 truncate-3-lines">
                                        @{{ element.description }}
                                    </p>
                                    <span class="badge bg-primary">Admin</span>
                                </div>
                            </div>
                        </template>
                    </draggable>

                    <div v-show="!status.tasks.length" class="flex-1 p-4 d-flex flex-column align-items-center justify-content-center h-100">
                        <span class="text-secondary">No tasks yet</span>
                        <button class="mt-1 btn btn-link p-0">
                            Add one
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Inisialisasi data dari controller Laravel
    const initialData = @json($statuses);

    // Buat aplikasi Vue
    const {
        createApp
    } = Vue

    const app = createApp({
        components: {
            draggable: VueDraggableNext.VueDraggableNext
        },
        data() {
            return {
                statuses: []
            };
        },
        methods: {
            handleTaskMoved(event) {
                const taskId = event.item.dataset.id;
                // Mencari elemen kolom tujuan untuk mendapatkan slug status
                // event.to adalah elemen DOM dari daftar tujuan. Kita cari elemen terdekat yang memiliki data-status-slug.
                const newStatusSlug = event.to.closest('[data-status-slug]').dataset.statusSlug;

                // Cari status baru berdasarkan slug
                const newStatus = this.statuses.find(s => s.slug === newStatusSlug);
                if (!newStatus) {
                    console.error('Status baru tidak ditemukan!');
                    return;
                }

                // Kirim pembaruan ke server
                fetch(`/tasks/${taskId}/move`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            // PASTIKAN tag meta CSRF token ada di template.app Anda
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            status_id: newStatus.id
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => console.log('Task moved successfully:', data))
                    .catch(error => {
                        console.error('Error moving task:', error);
                        // Opsional: Batalkan pergerakan tugas di UI jika terjadi error
                    });
            },
            // Fungsi pembantu untuk menentukan kelas Badge Bootstrap berdasarkan slug status
            getBadgeClass(slug) {
                switch (slug) {
                    case 'unassigned':
                        return 'bg-success'; // Menggunakan bg-success sesuai contoh Anda
                    case 'to-do':
                        return 'bg-secondary';
                    case 'in-progress':
                        return 'bg-warning text-dark';
                    case 'in-reviews':
                        return 'bg-primary';
                    case 'completed':
                        return 'bg-success';
                    default:
                        return 'bg-info';
                }
            }
        },
        created() {
            // 'clone' data agar tidak mengubah prop asli
            this.statuses = JSON.parse(JSON.stringify(initialData));
        }
    });

    app.mount('#app');
</script>

<style>
    /* Style untuk memotong teks hingga 3 baris */
    .truncate-3-lines {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Mengganti kursor untuk menunjukkan item bisa di-drag */
    .cursor-grab {
        cursor: grab;
    }

    /* Style untuk membuat list draggable terlihat lebih baik dalam konteks Bootstrap */
    .list-group-flush {
        padding-left: 0;
    }
</style>
@endpush