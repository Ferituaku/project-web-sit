@extends('akademik.mainAkd')
@section('title', 'Manajemen Ruang Kelas')

@section('content')
<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('akademik.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Manajemen Ruang Kelas</li>
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
                <div class="col-md-6 text-md-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Ruang
                    </button>
                    <button class="btn btn-secondary ms-2" id="refreshTable">
                        <i class="bi bi-arrow-clockwise me-2"></i>Refresh
                    </button>
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
                                @if($ruang->jadwalKuliah->isEmpty())
                                <span class="badge bg-success">Tersedia</span>
                                @else
                                <span class="badge bg-warning">Terjadwal</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-info me-1" onclick="editRoom({{ $ruang->id}})">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteRoom({{$ruang -> $id}})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
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

<!-- Add Room Modal -->
<div class="modal fade" id="addRoomModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Ruang Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addRoomForm">
                    <div class="mb-3">
                        <label class="form-label">Kode Ruang</label>
                        <input type="text" class="form-control" name="koderuang" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kapasitas</label>
                        <input type="number" class="form-control" name="kapasitas" required min="1">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="saveRoom()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Room Modal -->
<div class="modal fade" id="editRoomModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Ruang Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editRoomForm">
                    <input type="hidden" name="room_id">
                    <div class="mb-3">
                        <label class="form-label">Kode Ruang</label>
                        <input type="text" class="form-control" name="koderuang" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kapasitas</label>
                        <input type="number" class="form-control" name="kapasitas" required min="1">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="updateRoom()">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function saveRoom() {
        const form = document.getElementById('addRoomForm');
        const formData = new FormData(form);

        fetch('{{ route("akademik.ruangkelas.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showAlert('success', data.message);
                    $('#addRoomModal').modal('hide');
                    location.reload();
                } else {
                    showAlert('error', 'Terjadi kesalahan. Silakan coba lagi.');
                }
            })
            .catch(error => {
                showAlert('error', 'Terjadi kesalahan sistem.');
            });
    }

    function editRoom(id) {
        fetch(`/akademik/ruangkelas/${id}`)
            .then(response => response.json())
            .then(data => {
                const form = document.getElementById('editRoomForm');
                form.elements['room_id'].value = data.id;
                form.elements['koderuang'].value = data.koderuang;
                form.elements['kapasitas'].value = data.kapasitas;
                $('#editRoomModal').modal('show');
            });
    }

    function updateRoom() {
        const form = document.getElementById('editRoomForm');
        const formData = new FormData(form);
        const roomId = form.elements['room_id'].value;

        fetch(`/akademik/ruangkelas/${roomId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-HTTP-Method-Override': 'PUT'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showAlert('success', data.message);
                    $('#editRoomModal').modal('hide');
                    location.reload();
                } else {
                    showAlert('error', 'Terjadi kesalahan. Silakan coba lagi.');
                }
            })
            .catch(error => {
                showAlert('error', 'Terjadi kesalahan sistem.');
            });
    }

    function deleteRoom(id) {
        if (confirm('Apakah Anda yakin ingin menghapus ruang kelas ini?')) {
            fetch(`/akademik/ruangkelas/${id}`, {
                    method: 'DELETE',
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
                        showAlert('error', 'Terjadi kesalahan. Silakan coba lagi.');
                    }
                })
                .catch(error => {
                    showAlert('error', 'Terjadi kesalahan sistem.');
                });
        }
    }
</script>
@endsection