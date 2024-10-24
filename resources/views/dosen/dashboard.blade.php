@extends('dosen.mainDsn')
@section('title', 'Dashboard Dosen')

@section('content')

<!-- Page Content -->
<div class="container-fluid py-4" style="margin-top: 70px;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Dashboard Dosen</li>
        </ol>
    </nav>
    <div class="row g-4">
        <!-- User Info Card -->
        <div class="col-md-4">
            <div class="card h-100 border-0" style="background: linear-gradient(45deg, #4158D0, #C850C0);">
                <div class="card-body text-white">
                    <div class="text-center mb-3">
                        <img src="{{ asset('img/budosen.jpg') }}" alt="avatar" class="rounded-circle img-fluid" style="width: 100px;">
                    </div>
                    <h4 class="text-center">{{ auth()->user()->name }}</h4>
                    <p class="mb-1"><i class="bi bi-envelope me-2"></i>{{ auth()->user()->email }}</p>
                    <p class="mb-1"><i class="bi bi-person-badge me-2"></i>NIP: 139945678000</p>
                    <p class="mb-1"><i class="bi bi-telephone me-2"></i>(021) 765-43533</p>
                    <p class="mb-0"><i class="bi bi-geo-alt me-2"></i>Pati, Sukolilo</p>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-md-8">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card stat-card border-0 bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Mahasiswa Bimbingan</h5>
                            <p class="card-text display-4">42</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card border-0 bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Kelas Aktif</h5>
                            <p class="card-text display-4">5</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card border-0 bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">Penelitian Aktif</h5>
                            <p class="card-text display-4">3</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- IRS Status Information Card -->
        <div class="col-md-6">
            <div class="card border-0">
                <div class="card-body">
                    <h5 class="card-title mb-4">Status IRS Mahasiswa</h5>
                    <canvas id="irsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Upcoming Schedule -->
        <div class="col-md-6">
            <div class="card border-0">
                <div class="card-body">
                    <h5 class="card-title mb-4">Jadwal Mendatang</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Algoritma dan Pemrograman
                            <span class="badge bg-primary rounded-pill">09:00</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Basis Data
                            <span class="badge bg-primary rounded-pill">13:00</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Jaringan Komputer
                            <span class="badge bg-primary rounded-pill">15:30</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#linkDoswalVerifikasi').on('click', function(e) {
            e.preventDefault();
            let url = $(this).attr('href');

            $.get(url, function(data) {
                $('#contentArea').html(data);
            });
        });

        var ctx = document.getElementById('irsChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Belum', 'Sudah'],
                datasets: [{
                    label: 'Status Verifikasi IRS Mahasiswa',
                    data: [5, 35],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(75, 192, 192, 1)'
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
    });

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
</script>



@endsection