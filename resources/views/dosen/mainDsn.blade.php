@extends('layout')

@section('contentDsn')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

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

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        background-color: var(--primary-color);
        color: #ffffff;
    }

    .card {
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .stat-card {
        transition: transform 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }
</style>

<div class="d-flex">
    <!-- Sidebar -->
    <aside class="fixed-top sidebar vh-100 position-fixed" style="width: 250px;">
        <div class="d-flex flex-column p-3 h-100">
            <a href="#" class="navbar-brand d-flex align-items-center mb-4 ">
                <img src="{{ asset('img/Universitas-Diponegoro-Semarang-Logo.png') }}" alt="logo" class="img-fluid" style="height: 50px; width: 50px;">
                <span class="fs-5 fw-bold ms-2">SIT Undip</span>
            </a>
            <!-- Navigation Menu -->
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item mb-2">
                    <a href="{{route('dosen.dashboard')}}" class="nav-link d-flex active align-items-center">
                        <i class="bi bi-speedometer2 me-2"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{route('dosen.irs')}}" class="nav-link d-flex align-items-center">
                        <i class="bi bi-file-earmark-check me-2"></i>
                        Setujui IRS
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{route('dosen.lihatjadwal')}}" class="nav-link d-flex align-items-center">
                        <i class="bi bi-calendar me-2"></i>
                        Jadwal
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{route('dosen.konsultasi')}}" class="nav-link d-flex align-items-center">
                        <i class="bi bi-chat-dots me-2"></i>
                        Konsultasi Mahasiswa
                    </a>
                </li>
            </ul>
            <!-- Logout -->
            <div class="mt-auto">
                <a href="{{ route('logout') }}" class="nav-link text-danger-50 d-flex align-items-center">
                    <i class="bi bi-box-arrow-right me-2"></i> Log Out
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-grow-1" style="margin-left: 250px;">
        <!-- Header -->
        <header class="bg-white p-3 border-bottom shadow-sm fixed-top" style="margin-left: 250px;">
            <div class="d-flex justify-content-between align-items-center">
                <h3 style="visibility:hidden;">Dashboard Dosen</h3>
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('img/user.jpg') }}" alt="user" width="32" height="32" class="rounded-circle me-2">
                        <span class="text-dark">{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                        <li><a class="dropdown-item" href="#">Profil</a></li>
                        <li><a class="dropdown-item" href="#">Pengaturan</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="{{ route('logout') }}">Keluar</a></li>
                    </ul>
                </div>
            </div>
        </header>

        @yield('content')
        @yield('scriptDsn')

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


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

        window.addEventListener('postate', setActiveSidebarItem);
    });
</script>

@endsection