@extends('layout')

@section('contentKpd')

<style>
    :root {
        --primary-color: #4a90e2;
        --secondary-color: #f5f5f5;
        --text-color: #333333;
        --sidebar-width: 250px;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: var(--secondary-color);
        color: var(--text-color);
    }

    .sidebar {
        background-color: #ffffff;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    }

    .sidebar .nav-link {
        color: var(--text-color);
        transition: background-color 0.3s, color 0.3s;
    }


    .sidebar .nav-link.active {
        color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.1);
        border-left: 3px solid #0d6efd;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        background-color: var(--primary-color);
        color: #ffffff;
    }

    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease-in-out;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .table {
        background-color: #ffffff;
        border-radius: 10px;
        overflow: hidden;
    }

    .table thead th {
        background-color: var(--primary-color);
        color: #ffffff;
    }

    .badge[onclick] {
        cursor: pointer;
    }

    .badge[onclick]:hover {
        opacity: 0.9;
    }

    #rejectionReason {
        white-space: pre-wrap;
        color: #dc3545;
    }
</style>


<div class="d-flex">
    <!-- Sidebar -->
    <aside class="sidebar vh-100 position-fixed fixed-top" style="width: var(--sidebar-width);">
        <div class="d-flex flex-column p-3 h-100">
            <a href="#" class="navbar-brand d-flex align-items-center mb-4">
                <img src="{{ asset('img/Universitas-Diponegoro-Semarang-Logo.png') }}" alt="logo" class="img-fluid" style="height: 40px; width: 40px;">
                <span class="fs-5 fw-bold ms-2">SIT Undip</span>
            </a>
            <!-- Navigation Menu -->
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item mb-2">
                    <a href="{{route('kaprodi.dashboard')}}" class="nav-link active d-flex align-items-center">
                        <i class="bi bi-speedometer2 me-2"></i>
                        dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{route('kaprodi.buatjadwal')}}" class="nav-link d-flex align-items-center">
                        <i class="bi bi-calendar me-2"></i>
                        Manajemen Jadwal
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link d-flex align-items-center">
                        <i class="bi bi-person-lines-fill me-2"></i>
                        Status Mahasiswa
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link d-flex align-items-center">
                        <i class="bi bi-graph-up me-2"></i>
                        Statistik Akademik
                    </a>
                </li>
            </ul>
            <!-- Logout -->
            <div class="mt-auto">
                <a href="{{ route('logout') }}" class="nav-link text-danger d-flex align-items-center">
                    <i class="bi bi-box-arrow-right me-2"></i> Log Out
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-grow-1" style="margin-left: 260px;">
        <!-- Header -->
        <header class="bg-white p-3 border-bottom shadow-sm fixed-top" style="margin-left: 250px">
            <div class="d-flex justify-content-between align-items-center" style="margin-left: 135vh ">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('img/kaprodi.jpg') }}" alt="user" width="32" height="32" class="rounded-circle me-2">
                        <span class="text-dark">{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="{{ route('logout') }}">Keluar</a></li>
                    </ul>
                </div>
            </div>
        </header>
        <div class="container-fluid py-2" style="margin-top: 70px;">

            @yield('content')
            @yield('scriptKpd')
        </div>
    </main>
</div>
<!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        function setActiveSidebarItem() {
            // Dapatkan current route name dari Laravel
            const currentRoute = '{{ Route::currentRouteName() }}';

            // Pilih semua nav-link di sidebar
            const sidebarLinks = document.querySelectorAll('.sidebar .nav-link');

            sidebarLinks.forEach(link => {
                // Reset semua active state
                link.classList.remove('active');
                // Jika route cocok dengan current route, set active
                if (linkRoute && currentRoute === linkRoute) {
                    link.classList.add('active');
                }
            });
        }

        // Jalankan fungsi saat halaman dimuat
        setActiveSidebarItem();
    });
</script> -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function setActiveSidebarItem() {
            var currentPage = window.location.href;

            var sidebarLinks = document.querySelectorAll('.sidebar .nav-link')

            sidebarLinks.forEach(function(link) {
                link.classList.remove('active');

                if (currentPage == includes(link.getAttribute('href'))) {
                    link.classList.add('active');
                }
            });
        }
        setActiveSidebarItem();
    });
</script>


@endsections