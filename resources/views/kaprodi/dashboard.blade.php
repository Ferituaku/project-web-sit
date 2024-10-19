@extends('layout')
@section('title', 'Dashboard Dosen')
@section('contentKpd')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet" id="bootstrap-css">
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

@auth
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
                    <a href="#" class="nav-link active d-flex align-items-center">
                        <i class="bi bi-speedometer2 me-2"></i>
                        dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{route('kaprodi.buatjadwal')}}" class="nav-link d-flex align-items-center">
                        <i class="bi bi-calendar me-2"></i>
                        Buat Jadwal Kuliah
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

        <!-- Page Content -->
        <div class="container-fluid py-4" style="margin-top: 10px; margin-left:10px">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Dashboard Kaprodi</li>
                </ol>
            </nav>
            <div class="row g-4">
                <!-- User Info Card -->
                <div class="col-md-12">
                    <div class="card h-100" style="background-color: var(--primary-color)
                        ">
                        <div class="card-body d-flex flex-column flex-md-row align-items-center">
                            <div class="mb-3 mb-md-0 me-md-3">
                                <img src="{{ asset('img/kaprodi.jpg') }}" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                            </div>
                            <div class="text-center text-md-start">
                                <h4 class="text-white">Kaprodi {{ auth()->user()->name }}</h4>
                                <p class="text-white mb-1">{{ auth()->user()->email }}</p>
                                <p class="text-white mb-1">NIP: 139945678000</p>
                                <p class="text-white mb-1">No. Telp: (021) 765-43533</p>
                                <p class="text-white mb-0">Alamat: Pati, Sukolilo</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <h5 class="card-title">Total Mahasiswa</h5>
                            <p class="card-text h2">1,234</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h5 class="card-title">Rata-rata IPK</h5>
                            <p class="card-text h2">3.45</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <h5 class="card-title">Jumlah Dosen</h5>
                            <p class="card-text h2">45</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <h5 class="card-title">Mata Kuliah Aktif</h5>
                            <p class="card-text h2">78</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Status IRS Mahasiswa</h5>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>NIM</th>
                                            <th>Nama</th>
                                            <th>Semester</th>
                                            <th>Status</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>24010122130001</td>
                                            <td>John Doe</td>
                                            <td>5</td>
                                            <td><span class="badge bg-warning">Cuti</span></td>
                                        </tr>
                                        <tr>
                                            <td>24010123130002</td>
                                            <td>Jane Smith</td>
                                            <td>3</td>
                                            <td><span class="badge bg-success">Aktif</span></td>
                                        </tr>
                                        <tr>
                                            <td>24010124130006</td>
                                            <td>El Vinsen</td>
                                            <td>1</td>
                                            <td><span class="badge bg-success">Aktif</span></td>
                                        </tr>
                                        <!-- Add more rows as needed -->
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-secondary" style="margin-left: 300px;">Lihat lebih banyak</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic Performance Chart -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Performa Akademik Informatika</h5>
                            <canvas id="academicPerformanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('academicPerformanceChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['2019', '2020', '2021', '2022', '2023'],
                datasets: [{
                    label: 'Rata-rata IPK',
                    data: [3.2, 3.3, 3.4, 3.5, 3.45],
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 2.5,
                        max: 4.0
                    }
                }
            }
        });
    });
</script>
@endauth
@endsection