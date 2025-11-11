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
        #sidebar {
            width: 225px;
            /* Lebar default sidebar */
            /* transition: width 0.3s; */
        }

        #page-topbar {
            left: 0px;
        }

        #page-topbar,
        .main-content {
            margin-left: 225px;
            /* Disesuaikan dengan lebar sidebar */
            transition: margin-left 0.3s ease-in-out;
        }

        .dropdown .icon--arrow-bottom {
            display: none !important;
        }

        /* Aturan saat sidebar diminimize */
        .sidebar-minimized #sidebar {
            width: 70px;
        }

        /* Geser header dan konten utama saat sidebar minimize */
        .sidebar-minimized #page-topbar,
        .sidebar-minimized .main-content {
            margin-left: 70px;
        }

        /* Menyembunyikan elemen-elemen saat sidebar diminimize */
        .sidebar-minimized #sidebar .menu-title,
        .sidebar-minimized #sidebar .nav-link span,
        .sidebar-minimized #sidebar .logo-lg {
            display: none;
        }

        /* Menampilkan logo kecil saat sidebar diminimize */
        .sidebar-minimized #sidebar .logo-sm {
            display: block !important;
        }

        /* Hilangkan arrow dropdown di navbar user */
        #page-header-user-dropdown::after {
            display: none !important;
        }

        /* Buat breadcrumb di bawah navbar */
        .page-title-box {
            margin-top: 65px;
            border-bottom: 1px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
        }
    </style>
</head>

<body data-user-role="@json(Auth::user()->role->name ?? null)">
    <div id=" layout-wrapper" class="">
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
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script> --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script> --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> --}}

<script>
    // Inisialisasi DataTables
    $(document).ready(function() {
        $('#myTable').DataTable();
    });


    const toggleBtn = document.getElementById('sidebarToggle');
    const layoutWrapper = document.getElementById('layout-wrapper');

    if (toggleBtn && layoutWrapper) {
        toggleBtn.addEventListener('click', function() {
            layoutWrapper.classList.toggle('sidebar-minimized');
        });
    }
</script>

@stack('scripts')

</html>