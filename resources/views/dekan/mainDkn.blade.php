@extends('layout')

@section('contentDkn')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
</style>


<div class="d-flex">
    <!-- Sidebar -->
    <aside class="sidebar fixed-top bg-light vh-100 position-fixed shadow p-2 mb-5 bg-body-tertiary rounded" style="width: 250px;">
        <div class="d-flex flex-column p-3 h-100">
            <a href="#" class="navbar-brand d-flex align-items-center mb-4">
                <img src="{{ asset('img/Universitas-Diponegoro-Semarang-Logo.png') }}" alt="logo" class="img-fluid" style="height: 50px; width: 50px;">
                <span class="fs-5 fw-bold ms-2 text-dark">SIT Undip</span>
            </a>
            <!-- Navigation Menu -->
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item mb-2">
                    <a href="{{ route('dekan.dashboard') }}" class="nav-link d-flex align-items-center">
                        <i class="bi bi-speedometer2 me-2"></i>
                        Dashboard Dekan
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{route('dekan.persetujuan')}}" class="nav-link d-flex align-items-center">
                        <i class="bi bi-file-earmark-text me-2"></i>
                        Persetujuan Kelas
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{route('dekan.persetujuanJadwal')}}" class="nav-link d-flex align-items-center">
                        <i class=" bi bi-calendar-check me-2"></i>
                        Persetujuan Jadwal
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link d-flex align-items-center">
                        <i class="bi bi-graph-up me-2"></i>
                        Laporan Akademik
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
    <main class="flex-grow-1" style="margin-left: 250px;">
        <!-- Header -->
        <header class="bg-white p-3 border-bottom fixed-top" style="margin-left: 263px;">
            <div class="d-flex justify-content-end align-items-center">
                <div class="dropdown text-end flex flex-row items-center ms-auto justify-end gap-2">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="text-dark me-2">{{ auth()->user()->name }}</span>
                        <img src="{{ asset('img/PakAhmed.jpg') }}" alt="user" width="32" height="32" class="rounded-circle">
                    </a>
                    <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser">
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="{{ route('logout') }}">Keluar</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="container-fluid py-4" style="margin-top: 60px; margin-left:10px">


            @yield('content')
            @yield('scripts')

        </div>
    </main>
</div>