@extends('template.app')

@section('content')
<div class="row mb-3 pb-1">
    <div class="col-12">
        <div class="d-flex align-items-lg-center flex-lg-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-16 mb-1">Selamat datang kembali, {{ Auth::user()->name ?? 'User' }}!</h4>
                <p class="text-muted mb-0">Berikut adalah ringkasan aktivitas Anda.</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Tasks</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-2">{{ $totalTasks ?? 0 }}</h4>
                        {{-- LINK DIPERBARUI: Mengarah ke halaman kanban utama --}}
                        <a href="{{ route('tasks.index') }}" class="text-decoration-underline">Lihat semua task</a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-primary rounded fs-3">
                            <i class="fas fa-paste text-primary"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Pending</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-2">{{ $pendingTasks ?? 0 }}</h4>
                        {{-- LINK DIPERBARUI: Mengarah ke kolom 'to-do' --}}
                        <a href="{{ route('tasks.index') }}#status-column-to-do" class="text-decoration-underline">Lihat detail</a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-warning rounded fs-3">
                            <i class="fas fa-clock text-warning"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">In Progress</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-2">{{ $inProgressTasks ?? 0 }}</h4>
                        {{-- LINK DIPERBARUI: Mengarah ke kolom 'in-progress' --}}
                        <a href="{{ route('tasks.index') }}#status-column-in-progress" class="text-decoration-underline">Lihat detail</a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-info rounded fs-3">
                            <i class="fas fa-hourglass-half text-info"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Completed</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-2">{{ $completedTasks ?? 0 }}</h4>
                        {{-- LINK DIPERBARUI: Mengarah ke kolom 'completed' --}}
                        <a href="{{ route('tasks.index') }}#status-column-completed" class="text-decoration-underline">Lihat riwayat</a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-success rounded fs-3">
                            <i class="fas fa-check-circle text-success"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Task Progress per Week</h4>
            </div>
            <div class="card-body">
                <canvas id="taskProgressChart" style="width:100%; height:300px;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Recent Activity</h4>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    {{-- LOOP DATA DARI CONTROLLER --}}
                    @forelse($recentActivities as $task)
                    <li class="d-flex align-items-start mb-4">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-xs">
                                <span class="avatar-title bg-soft-success text-success rounded-circle fs-4">
                                    {{-- Ganti icon berdasarkan status --}}
                                    @if($task->status == 'Completed')
                                    <i class="fas fa-check"></i>
                                    @elseif($task->status == 'In Progress')
                                    <i class="fas fa-pen"></i>
                                    @else
                                    <i class="fas fa-plus"></i>
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0">Task "{{ Str::limit($task->title, 25) }}"
                                {{ $task->status == 'Completed' ? 'completed' : 'updated' }}
                            </p>
                            <small class="text-muted">
                                by {{ $task->user->name ?? 'System' }} - {{ $task->updated_at->diffForHumans() }}
                            </small>
                        </div>
                    </li>
                    @empty
                    <li classs="text-center text-muted">No recent activity.</li>
                    @endforelse

                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- SCRIPT PUSH (TIDAK BERUBAH) --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Ambil data dari Blade (yang dikirim Controller)
    const chartLabels = @json(array_keys($chartAdded));
    const chartDataAdded = @json(array_values($chartAdded));
    const chartDataCompleted = @json(array_values($chartCompleted));

    document.addEventListener("DOMContentLoaded", function() {
        if (document.getElementById('taskProgressChart')) {
            const ctx = document.getElementById('taskProgressChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartLabels.map(week => `Week ${week}`), // Membuat label 'Week 1', 'Week 2'
                    datasets: [{
                            label: 'Tasks Completed',
                            data: chartDataCompleted, // Data dari controller
                            backgroundColor: 'rgba(59, 130, 246, 0.2)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                        },
                        {
                            label: 'Tasks Added',
                            data: chartDataAdded, // Data dari controller
                            backgroundColor: 'rgba(209, 213, 219, 0.2)',
                            borderColor: 'rgba(107, 114, 128, 1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        }
    });
</script>
@endpush