@extends('dosen.mainDsn')
@section('title', 'Verifikasi IRS')

@section('content')
<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dosen.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Verifikasi IRS</li>
        </ol>
    </nav>

    <!-- Alert Section -->
    <div id="alert-container"></div>

    <!-- Main Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-dark">Daftar IRS Mahasiswa untuk Disetujui</h5>
        </div>

        <div class="card-body">
            <!-- Search Section -->
            <div class="row mb-3">
                <div class="col-md-4 offset-md-8">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari ruang kelas..." id="searchInput">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <td>1</td>
                        <td>24060</td>
                        <td>ferro</td>
                        <td>
                            <div class="col text-muted">
                                <span class="badge bg-warning">Belum Disetujui</span>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-info me-3" data-toggle="tooltip">
                                    Lihat Detail
                                </button>
                                <button class="btn btn-sm btn-success me-1" onclick="">
                                    <i class="bi bi-check-circle me-1"></i>Setujui
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="">
                                    <i class="bi bi-x-circle me-1"></i>Tolak
                                </button>

                            </div>
                        </td>
                        </td>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="row align-items-center mt-4">
                <div class="col-md-6">

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- <script>
    function showAlert(type, message) {
        const alertContainer = document.getElementById('alert-container');
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        alertContainer.innerHTML = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
    }

    function approveRoom(koderuang) {
        fetch(`/dekan/ruangkelas/${koderuang}/approve`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showAlert('success', data.message);
                    location.reload();
                } else {
                    showAlert('error', data.message || 'Terjadi kesalahan. Silakan coba lagi.');
                }
            })
            .catch(error => {
                showAlert('error', 'Terjadi kesalahan sistem.');
            });
    }

    function rejectRoom(koderuang) {
        fetch(`/dekan/ruangkelas/${koderuang}/reject`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showAlert('success', data.message);
                    location.reload();
                } else {
                    showAlert('error', data.message || 'Terjadi kesalahan. Silakan coba lagi.');
                }
            })
            .catch(error => {
                showAlert('error', 'Terjadi kesalahan sistem.');
            });
    }

    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        const searchValue = e.target.value.toLowerCase();
        const tableBody = document.querySelector('tbody');
        const rows = tableBody.getElementsByTagName('tr');

        for (let row of rows) {
            const koderuang = row.cells[1].textContent.toLowerCase();
            row.style.display = koderuang.includes(searchValue) ? '' : 'none';
        }
    });
</script> -->
@endsection