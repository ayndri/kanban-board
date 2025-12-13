<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">

<head>
    <meta charset="utf-8" />
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <link rel="shortcut icon" href="{{ asset('tamplate/assets/images/favicon.ico') }}">
    <link href="{{ asset('tamplate/assets/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('tamplate/assets/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('tamplate/assets/js/layout.js') }}"></script>
    <link href="{{ asset('tamplate/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('tamplate/assets/libs/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('tamplate/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <link href="{{ asset('tamplate/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('tamplate/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('tamplate/assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="https://kit.fontawesome.com/680ecce84d.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --vertical-menu-width: 250px;
            --vertical-menu-width-sm: 70px;
            --header-height: 70px;
            --bg-sidebar: #2c3e50;
            --bg-header: #ffffff;
            --transition-speed: 0.3s;
        }

        .app-menu {
            width: var(--vertical-menu-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1005;
            background-color: var(--bg-sidebar);
            transition: width var(--transition-speed) ease;
            overflow-x: hidden;
        }

        #page-topbar {
            position: fixed;
            top: 0;
            right: 0;
            left: var(--vertical-menu-width);
            height: var(--header-height);
            background-color: var(--bg-header);
            z-index: 1004;
            transition: left var(--transition-speed) ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }

        .main-content {
            margin-left: var(--vertical-menu-width);
            padding-top: calc(var(--header-height) + 20px);
            transition: margin-left var(--transition-speed) ease;
            min-height: 100vh;
        }

        .navbar-brand-box {
            height: var(--header-height);
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding-left: 1.5rem;
        }

        .logo-lg {
            display: block;
        }

        .logo-sm {
            display: none;
        }

        body[data-sidebar-size="sm"] .app-menu {
            width: var(--vertical-menu-width-sm);
        }

        body[data-sidebar-size="sm"] #page-topbar {
            left: var(--vertical-menu-width-sm);
        }

        body[data-sidebar-size="sm"] .main-content {
            margin-left: var(--vertical-menu-width-sm);
        }

        body[data-sidebar-size="sm"] .navbar-brand-box {
            justify-content: center;
            padding-left: 0;
        }

        body[data-sidebar-size="sm"] .logo-lg {
            display: none !important;
        }

        body[data-sidebar-size="sm"] .logo-sm {
            display: block !important;
        }

        body[data-sidebar-size="sm"] .logo-sm img {
            height: 30px;
            width: auto;
        }

        body[data-sidebar-size="sm"] .nav-link span,
        body[data-sidebar-size="sm"] .menu-title,
        body[data-sidebar-size="sm"] .menu-arrow {
            display: none !important;
        }

        body[data-sidebar-size="sm"] .navbar-nav .nav-link {
            display: flex;
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }

        body[data-sidebar-size="sm"] .navbar-nav .nav-link i {
            margin-right: 0;
            font-size: 1.2rem;
        }

        .dropdown-menu {
            z-index: 1050 !important;
            position: absolute !important;
        }

        .topbar-user .dropdown-menu {
            top: 100% !important;
            right: 0 !important;
            left: auto !important;
            transform: none !important;
            margin-top: 0 !important;
        }
    </style>
</head>

<body data-user-role="@json(Auth::user()->role->name ?? null)">
    <div id="layout-wrapper" class="">
        @include('template.navbar')
        @include('template.sidebar')
        <div class="breadcrumb-wrapper">
            @yield('breadcrumb')
        </div>


        <div class="main-content">
            <div class="">
                <div class="container-fluid">

                    @yield('content')

                </div>
            </div>
        </div>
    </div>
</body>

{{-- <footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <script>document.write(new Date().getFullYear())</script>
            </div>
            <div class="col-sm-6">
                <div class="text-sm-end d-none d-sm-block">

                </div>
            </div>
        </div>
    </div>
</footer> --}}

<script src="{{ asset('tamplate/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('tamplate/assets/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
<script src="{{ asset('tamplate/assets/js/pages/dashboard-ecommerce.init.js') }}"></script>
<script src="{{ asset('tamplate/assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
<script src="{{ asset('tamplate/assets/libs/jsvectormap/maps/world-merc.js') }}"></script>
<script src="{{ asset('tamplate/assets/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('tamplate/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('tamplate/assets/libs/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('tamplate/assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('tamplate/assets/libs/node-waves/waves.min.js') }}"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script src="{{ asset('tamplate/assets/js/plugins.js')}}"></script>
<script src="{{ asset('tamplate/assets/js/app.js')}}"></script>

<script>
    // Inisialisasi DataTables
    $(document).ready(function() {
        if ($.fn.DataTable) {
            $('#myTable').DataTable();
        }
    });

    // Fungsi Toggle Sidebar
    window.toggleSidebar = function() {
        const htmlElement = document.documentElement;
        const bodyElement = document.body;
        let currentSize = htmlElement.getAttribute('data-sidebar-size') || bodyElement.getAttribute('data-sidebar-size');

        if (currentSize === 'lg' || !currentSize) {
            htmlElement.setAttribute('data-sidebar-size', 'sm');
            bodyElement.setAttribute('data-sidebar-size', 'sm');
        } else {
            htmlElement.setAttribute('data-sidebar-size', 'lg');
            bodyElement.setAttribute('data-sidebar-size', 'lg');
        }
    };
</script>

@stack('scripts')
</body>

</html>