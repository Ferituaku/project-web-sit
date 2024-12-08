@extends('dosen.mainDsn')
@section('title', 'Jadwal Dosen')

@section('content')

<!-- Page Content -->
<div class="container-fluid py-4" style="margin-top: 70px;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Jadwal Dosen</li>
        </ol>
    </nav>
    <div class="row g-4">

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