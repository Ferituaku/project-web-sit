@extends('dekan.mainDkn')
@section('title', 'Persetujuan Ruang Kelas')

@section('content')
<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dekan.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Persetujuan Ruang Kelas</li>
        </ol>
    </nav>

    <!-- Alert Section -->
    <div id="alert-container"></div>

    <!-- Main Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-dark">Daftar Ruang Kelas untuk Disetujui</h5>
        </div>

        <div class="card-body">
            <!-- Search Section -->
            <div class="row mb-3">
                <div class="col-md-6 offset-md-6">
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
                            <th>Kode Ruang</th>
                            <th>Kapasitas</th>
                            <th>Program Studi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ruangKelas as $index => $ruang)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $ruang->koderuang }}</td>
                            <td>{{ $ruang->kapasitas }} orang</td>
                            <td>
                                @php
                                $programStudiNama = $programStudi->firstWhere('id', $ruang->program_studi_id)->nama;
                                @endphp
                                {{ $programStudiNama }}
                            </td>
                            <td>
                                @if($ruang->approval == 0)
                                <span class="badge bg-warning">Pending</span>
                                @elseif($ruang->approval == 1)
                                <span class="badge bg-success">Disetujui</span>
                                @elseif($ruang->approval == 2)
                                <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                @if($ruang->approval == 0)
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-success me-1" onclick="approveRoom('{{ $ruang->koderuang }}')">
                                        <i class="bi bi-check-circle me-1"></i>Setujui
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="rejectRoom('{{ $ruang->koderuang }}')">
                                        <i class="bi bi-x-circle me-1"></i>Tolak
                                    </button>
                                </div>
                                @elseif($ruang->approval == 1)
                                <span class="text-muted">Sudah disetujui</span>
                                @elseif($ruang->approval == 2)
                                <span class="text-muted">Ditolak</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            <!-- Pagination -->
            @if($ruangKelas->total() > 0)
            <div class="card-footer d-flex justify-content-between align-items-center py-3">
                <div class="text-sm text-muted">
                    Menampilkan {{ $ruangKelas->firstItem() }} - {{ $ruangKelas->lastItem() }}
                    dari {{ $ruangKelas->total() }} ruang kelas
                </div>
                <div>
                    {{ $ruangKelas->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        const searchValue = e.target.value.toLowerCase();
        const tableBody = document.querySelector('tbody');
        const rows = tableBody.getElementsByTagName('tr');

        for (let row of rows) {
            const koderuang = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            row.style.display = koderuang.includes(searchValue) ? '' : 'none';
        }
    });

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
</script>
@endsection