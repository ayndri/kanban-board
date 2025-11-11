<div class="app-menu navbar-menu py-3" id="sidebar">
    <div class="navbar-brand-box">
        <a href="{{ route('home') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('image/logo.png') }}" alt="Small Logo" height="40">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('tamplate/assets/images/logopt.png') }}" alt="Logo" height="30">
            </span>
        </a>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span>UTAMA</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ Request::routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="ri-dashboard-2-line"></i> <span>Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ Request::routeIs('tasks.*') ? 'active' : '' }}" href="{{ route('tasks.index') }}">
                        <i class="ri-task-line"></i> <span>Tasks</span>
                    </a>
                </li>

                <li class="menu-title"><span>PENGATURAN</span></li>

                <!-- <li class="nav-item">
                    <a class="nav-link menu-link {{ Request::routeIs('profile') ? 'active' : '' }}" href="{{ route('profile') }}">
                        <i class="ri-account-circle-line"></i> <span>Profil Saya</span>
                    </a>
                </li> -->

                {{--
                    Kita cek apakah user sudah login,
                    apakah relasi 'role'-nya ada,
                    dan apakah nama role-nya adalah 'admin'.
                    Ini adalah cara aman untuk menghindari error jika 'role' null.
                --}}
                @if(Auth::check() && Auth::user()->role && Auth::user()->role->name == 'admin')

                <li class="nav-item">
                    <a class="nav-link menu-link {{ Request::routeIs('employees.*') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                        <i class="ri-group-line"></i> <span>Data Karyawan</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ Request::routeIs('divisions.*') ? 'active' : '' }}" href="{{ route('divisions.index') }}">
                        <i class="ri-building-line"></i> <span>Data Divisi</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ Request::routeIs('roles.*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                        <i class="ri-shield-user-line"></i> <span>User Roles</span>
                    </a>
                </li>

                @endif
            </ul>
        </div>
    </div>
</div>