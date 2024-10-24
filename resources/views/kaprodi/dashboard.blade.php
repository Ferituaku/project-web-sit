@extends('kaprodi.mainKpd')
@section('title', 'Dashboard Kaprodi')

@section('content')

<!-- Page Content -->

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
                    <button type="button" class="btn" style="margin-left: 300px;">Lihat lebih banyak</button>
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

@endsection

@section('scriptKpd')
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

@endsection