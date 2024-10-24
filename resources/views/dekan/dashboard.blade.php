@extends('dekan.mainDkn')
@section('title', 'Dashboard Dekan')
@section('content')

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
@endsection
@section('scriptDkn')
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
@endsection