@extends('layout')
@section('title', 'Dashboard Dekan')
@section('contentDkn')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>



@auth
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
                    <a href="#" class="nav-link active d-flex align-items-center">
                        <i class="bi bi-speedometer2 me-2"></i>
                        Dashboard Dekan
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link text-muted d-flex align-items-center">
                        <i class="bi bi-file-earmark-text me-2"></i>
                        Laporan Akademik
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link text-muted d-flex align-items-center">
                        <i class="bi bi-people me-2"></i>
                        Data Fakultas
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link text-muted d-flex align-items-center">
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
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Dashboard Dekan</li>
                </ol>
            </nav>
            <div class="row g-4">
                <!-- User Info Card -->
                <div class="col-md-12">
                    <div class="card h-100" style="border-radius: 1rem;  background: hsla(199, 97%, 66%, 1);
                        background: linear-gradient(180deg, hsla(199, 97%, 66%, 1) 0%, hsla(254, 62%, 49%, 1) 100%);">
                        <div class="card-body d-flex flex-column flex-md-row align-items-center">
                            <div class="mb-3 mb-md-0 me-md-3">
                                <img src="{{ asset('img/PakAhmed.jpg') }}" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                            </div>
                            <div class="text-center text-md-start">
                                <h4 class="text-white">{{ auth()->user()->name }}</h4>
                                <p class="text-white mb-1">{{ auth()->user()->email }}</p>
                                <p class="text-white mb-1">NIP: 139945678000</p>
                                <p class="text-white mb-1">No. Telp: (021) 765-43533</p>
                                <p class="text-white mb-0">Alamat: Semarang, Jawa Tengah</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fakultas Overview Card -->
                <div class="col-md-8">
                    <div class="card" style="border-color:blue; border-radius:1rem; background: rgba(100, 100, 100, 0);">
                        <div class="card-body">
                            <h3 class="mb-4 text-center">Ringkasan Fakultas</h3>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">Jumlah Prodi</h5>
                                            <p class="card-text display-4">5</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">Total Mahasiswa</h5>
                                            <p class="card-text display-4">1250</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-info text-white">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">Total Dosen</h5>
                                            <p class="card-text display-4">75</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistik -->
                <div class="col-md-4">
                    <div class="card" style="border-color:blue; border-radius:1rem; background: rgba(100, 100, 100, 0);">
                        <div class="card-body">
                            <h3 class="mb-4 text-center">Statistik Akademik</h3>
                            <canvas id="akademikChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('akademikChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['IPK Rata-rata', 'Kelulusan', 'Penelitian'],
            datasets: [{
                label: 'Nilai',
                data: [3.5, 85, 65],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

@endauth
@endsection