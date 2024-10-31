@extends('dekan.mainDkn')
@section('title', 'Persetujuan Ruang Kelas')

@section('content')
<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dekanat.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Persetujuan Ruang Kelas</li>
        </ol>
    </nav>

    <!-- Alert Section -->
    <div id="alert-container"></div>

    <!-- Main Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0 text-dark">Data Ruang Kelas</h5>
                </div>
            </div>
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
                                @if($ruang->jadwalKuliah->where('approved', true)->count() > 0)
                                <span class="badge bg-success">Disetujui</span>
                                @else
                                <span class="badge bg-warning">Belum Disetujui</span>
                                @endif
                            </td>
                            <td>
                                @if($ruang->jadwalKuliah->where('approved', false)->count() > 0)
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-success" onclick="approveRoom('{{ $ruang->koderuang }}')">
                                        <i class="bi bi-check-circle me-1"></i>Setujui
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="rejectRoom('{{ $ruang->koderuang }}')">
                                        <i class="bi bi-x-circle me-1"></i>Tolak
                                    </button>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="row align-items-center mt-4">
                <div class="col-md-6">
                    {{ $ruangKelas->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Room Modal -->
<div class="modal fade" id="approveRoomModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Setujui Ruang Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menyetujui ruang kelas ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" onclick="confirmApproveRoom()">Setujui</button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Room Modal -->
<div class="modal fade" id="rejectRoomModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Ruang Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menolak ruang kelas ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" onclick="confirmRejectRoom()">Tolak</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function showAlert(type, message) {
        // Same as before
    }

    function approveRoom(koderuang) {
        $('#approveRoomModal').modal('show');
        // Set the koderuang value for the confirmation button
        document.getElementById('confirmApproveRoom').dataset.koderuang = koderuang;
    }

    function confirmApproveRoom() {
        const koderuang = document.getElementById('confirmApproveRoom').dataset.koderuang;
        fetch(`/dekanat/ruangkelas/${koderuang}/approve`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showAlert('success', data.message);
                    $('#approveRoomModal').modal('hide');
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
        $('#rejectRoomModal').modal('show');
        // Set the koderuang value for the confirmation button
        document.getElementById('confirmRejectRoom').dataset.koderuang = koderuang;
    }

    function confirmRejectRoom() {
        const koderuang = document.getElementById('confirmRejectRoom').dataset.koderuang;
        fetch(`/dekanat/ruangkelas/${koderuang}/reject`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showAlert('success', data.message);
                    $('#rejectRoomModal').modal('hide');
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
    // Same as before
</script>
@endsection