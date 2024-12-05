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
            <h5 class="mb-0 text-dark">Daftar IRS Mahasiswa untuk Diverifikasi</h5>
        </div>

        <div class="card-body">
            <!-- Search Section -->
            <div class="row mb-3">
                <div class="col-md-6 offset-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari mahasiswa..." id="searchInput">
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
                            <th>Semester</th>
                            <th>Total SKS</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($irs as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->mahasiswa->nim }}</td>
                            <td>{{ $item->mahasiswa->name }}</td>
                            <td>{{ $item->semester }}</td>
                            <td>{{ $item->total_sks }} SKS</td>
                            <td>
                                @if($item->approval == 0)
                                <span class="badge bg-warning">Pending</span>
                                @elseif($item->approval == 1)
                                <span class="badge bg-success">Disetujui</span>
                                @elseif($item->approval == 2)
                                <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                @if($item->approval == 0)
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-info me-1" 
                                            onclick="showIRSDetail('{{ $item->id }}')" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#irsDetailModal">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </button>
                                    <button class="btn btn-sm btn-success me-1" onclick="approveIRS('{{ $item->id }}')">
                                        <i class="bi bi-check-circle me-1"></i>Setujui
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="rejectIRS('{{ $item->id }}')">
                                        <i class="bi bi-x-circle me-1"></i>Tolak
                                    </button>
                                </div>
                                @elseif($item->approval == 1)
                                <span class="text-muted">Sudah disetujui</span>
                                @elseif($item->approval == 2)
                                <span class="text-muted">Ditolak</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($irs->total() > 0)
            <div class="card-footer d-flex justify-content-between align-items-center py-3">
                <div class="text-sm text-muted">
                    Menampilkan {{ $irs->firstItem() }} - {{ $irs->lastItem() }}
                    dari {{ $irs->total() }} IRS
                </div>
                <div>
                    {{ $irs->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- IRS Detail Modal -->
<div class="modal fade" id="irsDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail IRS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="irsDetailContent">
                    <div class="mb-3">
                        <h6>Total SKS: <span id="total-sks">0</span>/24</h6>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 0%;"
                                aria-valuenow="0" aria-valuemin="0" aria-valuemax="24">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Kode MK</th>
                                    <th>Mata Kuliah</th>
                                    <th>SKS</th>
                                    <th>Kelas</th>
                                    <th>Jadwal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="selected-courses-table">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
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

    function approveIRS(id) {
        fetch(`/dosen/irs/${id}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showAlert('success', 'IRS berhasil disetujui');
                location.reload();
            } else {
                showAlert('error', data.message || 'Terjadi kesalahan saat menyetujui IRS');
            }
        })
        .catch(error => {
            showAlert('error', 'Terjadi kesalahan sistem');
        });
    }

    function rejectIRS(id) {
        fetch(`/dosen/irs/${id}/reject`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showAlert('success', 'IRS berhasil ditolak');
                location.reload();
            } else {
                showAlert('error', data.message || 'Terjadi kesalahan saat menolak IRS');
            }
        })
        .catch(error => {
            showAlert('error', 'Terjadi kesalahan sistem');
        });
    }
</script>
@endsection