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
                            <th>Program Studi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ruangKelas as $index => $ruang)
                        <tr>
                            <td>{{ $loop->iteration + $ruangKelas->firstItem() - 1 }}</td>
                            <td>{{ $ruang->koderuang }}</td>
                            <td>{{ $ruang->kapasitas }} orang</td>
                            <td>{{ $ruang->program_studi_id? $ruang->programStudi->nama : 'Belum diatur' }}</td>
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
                                    <button class="btn btn-sm btn-info me-1" onclick="editRoom('{{ $ruang->koderuang }}')">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteRoom('{{ $ruang->koderuang }}')">
                                        <i class="bi bi-trash"></i>
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


            <div>
                {{ $ruangKelas->links('vendor.pagination.bootstrap-5') }}
            </div>


        </div>
    </div>
</div>

<div class="modal fade" id="addRoomModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Ruang Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addRoomForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Kode Ruang</label>
                        <input type="text" class="form-control" name="koderuang" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kapasitas</label>
                        <input type="number" class="form-control" name="kapasitas" required min="1">
                    </div>
                    <div class="mb-3">
                        <label for="program_studi_id" class="form-label">Program Studi</label>
                        <select class="form-select" id="program_studi_id" name="program_studi_id">
                            <option value="" selected>Pilih Program Studi</option>
                            @foreach ($programStudi as $ps)
                            <option value="{{ $ps->id }}">{{ $ps->nama }}</option>
                            @endforeach
                        </select>
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
                    <input type="hidden" name="edit_koderuang">
                    <div class="mb-3">
                        <label for="new_koderuang" class="form-label">Kode Ruang</label>
                        <input type="text" id="new_koderuang" name="new_koderuang" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="kapasitas" class="form-label">Kapasitas</label>
                        <input type="number" id="kapasitas" name="kapasitas" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="program_studi_id" class="form-label">Program Studi</label>
                        <select id="program_studi_id" name="program_studi_id" class="form-select">
                            <option value="">Pilih Program Studi</option>
                            <!-- Options will be populated dynamically -->
                        </select>
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
    async function fetchApi(url, options) {
        try {
            const response = await fetch(url, options);
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Fetch Error:', error);
            throw new Error('Terjadi kesalahan sistem.');
        }
    }
    async function saveRoom() {
        const form = document.getElementById('addRoomForm');
        const formData = new FormData(form);

        try {
            const data = await fetchApi('{{ route("akademik.ruangkelas.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            if (data.status === 'success') {
                showAlert('success', data.message);
                $('#addRoomModal').modal('hide');
                location.reload();
            } else {
                showAlert('error', data.message || 'Terjadi kesalahan. Silakan coba lagi.');
            }
        } catch (error) {
            showAlert('error', error.message);
        }
    }



    async function updateRoom() {
        const form = document.getElementById('editRoomForm');
        const koderuang = form.elements['edit_koderuang'].value;
        const formData = new FormData(form);

        try {
            const data = await fetchApi(`/akademik/ruangkelas/${koderuang}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-HTTP-Method-Override': 'PUT'
                }
            });

            if (data.status === 'success') {
                showAlert('success', data.message);
                $('#editRoomModal').modal('hide');
                location.reload();
            } else {
                showAlert('error', data.message || 'Terjadi kesalahan. Silakan coba lagi.');
            }
        } catch (error) {
            showAlert('error', error.message);
        }
    }

    async function deleteRoom(koderuang) {
        if (confirm('Apakah Anda yakin ingin menghapus ruang kelas ini?')) {
            try {
                const data = await fetchApi(`/akademik/ruangkelas/${koderuang}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                if (data.status === 'success') {
                    showAlert('success', data.message);
                    location.reload();
                } else {
                    showAlert('error', data.message || 'Terjadi kesalahan. Silakan coba lagi.');
                }
            } catch (error) {
                showAlert('error', error.message);
            }
        }
    }

    function editRoom(koderuang) {
        fetch(`/akademik/ruangkelas/${koderuang}/edit`, {
                headers: {
                    'Accept': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'error') {
                    showAlert('error', data.message);
                    return;
                }
                const form = document.getElementById('editRoomForm');
                form.elements['edit_koderuang'].value = data.ruangKelas.koderuang;
                form.elements['new_koderuang'].value = data.ruangKelas.koderuang;
                form.elements['kapasitas'].value = data.ruangKelas.kapasitas;

                // Populate program studi dropdown
                const programStudiSelect = form.elements['program_studi_id'];
                programStudiSelect.innerHTML = `<option value="">Pilih Program Studi</option>`;
                data.programStudi.forEach(program => {
                    programStudiSelect.innerHTML += `<option value="${program.id}" ${
                    program.id === data.ruangKelas.program_studi_id ? 'selected' : ''
                }>${program.nama}</option>`;
                });

                $('#editRoomModal').modal('show');
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Terjadi kesalahan saat mengambil data ruang.');
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

    // Refresh button
    document.getElementById('refreshTable').addEventListener('click', function() {
        location.reload();
    });
</script>
@endsection