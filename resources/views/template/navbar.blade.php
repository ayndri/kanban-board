<header id="page-topbar">
    <div class="navbar-header p-0">
        <div class="d-flex justify-content-between align-items-center w-100 px-3">
            <div class="d-flex align-items-center">
                <button type="button"
                    class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                    id="sidebarToggle"
                    onclick="toggleSidebar()"> <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

                <form class="app-search d-none d-md-block ms-2">
                    <div class="position-relative">
                        <input type="text" class="form-control" placeholder="Search..." autocomplete="off">
                        <span class="mdi mdi-magnify search-widget-icon"></span>
                    </div>
                </form>
            </div>

            <div class="d-flex align-items-center">

                <div class="dropdown topbar-head-dropdown ms-1 header-item">
                    {{-- 1. Penambahan ID pada Tombol --}}
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                        id="page-header-notification-dropdown" {{-- ID ini ditambahkan --}}
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class='bx bxs-bell fs-22'></i>

                        {{-- 2. Badge Notifikasi Dinamis --}}
                        @if($unreadNotificationsCount > 0)
                        <span class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger">
                            {{ $unreadNotificationsCount }}
                            <span class="visually-hidden">unread messages</span>
                        </span>
                        @endif
                    </button>

                    {{-- 3. Isi Dropdown Notifikasi --}}
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notification-dropdown">
                        <div class="dropdown-head bg-primary bg-soft text-primary rounded-top p-3">
                            <div class="d-flex align-items-center">
                                <h6 class="m-0 fs-16 fw-semibold"> Notifications </h6>
                                @if($unreadNotificationsCount > 0)
                                <span class="badge badge-soft-danger fs-13 ms-2">{{ $unreadNotificationsCount }} New</span>
                                @endif
                            </div>
                        </div>

                        {{-- Daftar Notifikasi --}}
                        <div class="p-3" style="max-height: 300px;" data-simplebar>
                            @forelse($unreadNotifications as $notification)
                            <a href="{{ route('notifications.markAsRead', $notification->id) }}" class="text-reset notification-item d-block dropdown-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-xs">
                                            <span class="avatar-title bg-soft-info text-info rounded-circle fs-16">
                                                <i class="{{ $notification->data['icon'] ?? 'bx bx-info-circle' }}"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fs-14">{{ $notification->data['message'] ?? 'New notification' }}</h6>
                                        <div class="fs-12 text-muted">
                                            <p class="mb-0"><i class="mdi mdi-clock-outline"></i> {{ $notification->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            @empty
                            <div class="text-center text-muted py-3">
                                <p class="mb-0">No new notifications</p>
                            </div>
                            @endforelse
                        </div>

                        {{-- Tombol "Mark all as read" --}}
                        @if($unreadNotificationsCount > 0)
                        <div class="p-2 border-top">
                            <form action="{{ route('notifications.markAllAsRead') }}" method="POST" id="mark-all-read-form" style="display: none;">
                                @csrf
                            </form>
                            <a class="btn btn-sm btn-link fs-13 text-muted d-block text-center" href="javascript:void(0)"
                                onclick="event.preventDefault(); document.getElementById('mark-all-read-form').submit();">
                                <i class="mdi mdi-check-all me-1"></i> Mark all as read
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="dropdown ms-sm-3 header-item topbar-user">
                    <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            @if (Auth::user() && Auth::user()->photo)
                            <img class="rounded-circle header-profile-user" src="{{ asset('storage/' . Auth::user()->photo) }}" alt="Header Avatar">
                            @else
                            <div class="avatar-xs d-flex align-items-center justify-content-center rounded-circle bg-soft-primary text-primary fw-medium">
                                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                            </div>
                            @endif
                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{ Auth::user()->name ?? 'Guest User' }}</span>
                                <span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">
                                    {{ Auth::user()->employee?->job_title ?? ucfirst(Auth::user()->role?->name) ?? 'Member' }}
                                </span>
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <h6 class="dropdown-header">Selamat Datang, {{ strtok(Auth::user()->name ?? 'User', " ") }}!</h6>
                        <a class="dropdown-item" href="{{ route('profile') }}">
                            <i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i>
                            <span class="align-middle">Profile</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" id="logout-link">
                            <i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i>
                            <span class="align-middle" data-key="t-logout">Logout</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</header>

{{-- SCRIPT ANDA UNTUK LOGOUT (Tidak diubah) --}}
@push('scripts')
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize all dropdowns manually
        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
        var dropdownList = dropdownElementList.map(function(dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl);
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const logoutLink = document.getElementById('logout-link');
        if (logoutLink) {
            logoutLink.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda akan keluar dari sesi ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Logout!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('logout-form').submit();
                    }
                });
            });
        }


    });
</script>
@endpush
@endpush