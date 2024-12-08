@extends('kaprodi.mainKpd')
@section('title', 'Manajemen Mata Kuliah')

@section('content')
<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('kaprodi.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Manajemen Jadwal</li>
        </ol>
    </nav>

    <!-- Alert Section -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Main Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0 text-dark">Data Mata Kuliah</h5>
                </div>
                <div class="col-md-6 text-md-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMatkulModal">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Mata Kuliah
                    </button>
                    <button class="btn btn-secondary ms-2" id="refreshTable">
                        <i class="bi bi-arrow-clockwise me-2"></i>Refresh
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Search and Filter Section -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <select class="form-select" id="semester-filter">
                        <option value="">Semua Semester</option>
                        @for($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}">Semester {{ $i }}</option>
                            @endfor
                    </select>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari jadwal..." id="searchInput">
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
                            <th>Kode MK</th>
                            <th>Nama Matkul</th>
                            <th>Semester
                                <button id="sortSemester" class="btn btn-sm p-0" title="Urutkan Semester">
                                    <i id="sortIcon" class="bi bi-sort-down"></i>
                                </button>
                            </th>
                            <th>SKS</th>
                            <th>Program Studi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mataKuliah as $index => $matkul)
                        <tr>
                            <td>{{ $loop->iteration + $mataKuliah->firstItem() - 1}}</td>
                            <td>{{ $matkul->kodemk }}</td>
                            <td>{{ $matkul->nama_mk }}</td>
                            <td>
                                {{$matkul->semester}}
                            </td>
                            <td>{{ $matkul->sks }}</td>
                            <td>{{ $matkul->prodi_id? $matkul->prodi->nama : 'Belum diatur' }}</td>

                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-info me-1" onclick="editMatkul('{{ $matkul->kodemk }}')">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteMatkul('{{ $matkul->kodemk }}')">
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
            <div>
                {{ $mataKuliah->links('vendor.pagination.bootstrap-5') }}
            </div>

        </div>
    </div>
</div>


<!-- Add Modal -->
<div class="modal fade" id="addMatkulModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Mata Kuliah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addMatkulForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Kode Mata Kuliah</label>
                        <input type="number" class="form-control" name="kodemk" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Mata Kuliah</label>
                        <input type="text" class="form-control" name="nama_mk" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Semester</label>
                        <select class="form-select" name="semester" required>
                            <option value="">Pilih Semester</option>
                            @for($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}">Semester {{ $i }}</option>
                                @endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">SKS</label>
                        <input type="number" class="form-control" name="sks" required min="1" max="4">
                    </div>
                    <!-- Removed prodi selection as it's automatic -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="saveMatkul()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editMatkulModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Mata Kuliah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editMatkulForm">
                    <input type="hidden" name="edit_kodemk">
                    <div class="mb-3">
                        <label for="new_kodemk" class="form-label">Kode Mata Kuliah</label>
                        <input type="number" id="new_kodemk" name="new_kodemk" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_mk" class="form-label">Nama Mata Kuliah</label>
                        <input type="text" id="nama_mk" name="nama_mk" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="sks" class="form-label">SKS</label>
                        <input type="number" id="sks" name="sks" class="form-control" min="1" max="4" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Semester</label>
                        <select class="form-select" name="semester" required>
                            <option value="">Pilih Semester</option>
                            @for($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}">Semester {{ $i }}</option>
                                @endfor
                        </select>
                    </div>
                    <!-- Removed prodi selection as it's automatic -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="updateMatakuliah()">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>

@endsection


@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Flag untuk melacak arah urutan: true = ascending, false = descending
        let isAscending = true;
        // Fungsi untuk mengurutkan tabel berdasarkan kolom semester
        function sortTableBySemester() {
            const tbody = document.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));

            rows.sort((a, b) => {
                const semesterA = parseInt(a.querySelector('td:nth-child(4)').textContent);
                const semesterB = parseInt(b.querySelector('td:nth-child(4)').textContent);
                return isAscending ? semesterA - semesterB : semesterB - semesterA;
            });

            // Bersihkan isi tabel dan tambahkan baris yang sudah diurutkan
            tbody.innerHTML = '';
            rows.forEach(row => tbody.appendChild(row));

            // Perbarui ikon panah berdasarkan urutan
            document.getElementById('sortIcon').className = isAscending ? 'bi btn-outline-light  bi-sort-down' : 'bi btn-outline-light bi-sort-up';

            // Ubah arah urutan untuk klik berikutnya
            isAscending = !isAscending;
        }

        // Event listener untuk tombol sort
        document.getElementById('sortSemester').addEventListener('click', sortTableBySemester);


        // Show Add Modal
        const addButton = document.querySelector('[data-bs-target="#addMatkulModal"]');
        if (addButton) {
            addButton.addEventListener('click', function() {
                const modal = new bootstrap.Modal(document.getElementById('addMatkulModal'));
                document.getElementById('addMatkulForm').reset(); // Reset form
                modal.show();
            });
        }

        // Save New Mata Kuliah
        window.saveMatkul = function() {
            const form = document.getElementById('addMatkulForm');
            const formData = new FormData(form);
            const data = {};
            formData.forEach((value, key) => data[key] = value);

            fetch('{{ route("kaprodi.buatmatkul") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Tutup modal sebelum menampilkan alert
                        const addModal = document.getElementById('addMatkulModal');
                        const modalInstance = bootstrap.Modal.getInstance(addModal);
                        if (modalInstance) {
                            modalInstance.hide();
                            // Remove modal backdrop if exists
                            const backdrop = document.querySelector('.modal-backdrop');
                            if (backdrop) backdrop.remove();
                            document.body.classList.remove('modal-open');
                        }

                        alert(data.message);
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menyimpan data');
                });
        };

        // Edit Mata Kuliah
        window.editMatkul = function(kodemk) {
            fetch(`/kaprodi/matkul/edit/${kodemk}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const modal = new bootstrap.Modal(document.getElementById('editMatkulModal'));

                        // Populate form fields
                        document.querySelector('#editMatkulForm input[name="edit_kodemk"]').value = kodemk;
                        document.querySelector('#editMatkulForm input[name="new_kodemk"]').value = data.matakuliah.kodemk;
                        document.querySelector('#editMatkulForm input[name="nama_mk"]').value = data.matakuliah.nama_mk;
                        document.querySelector('#editMatkulForm input[name="sks"]').value = data.matakuliah.sks;
                        document.querySelector('#editMatkulForm select[name="semester"]').value = data.matakuliah.semester;

                        modal.show();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengambil data');
                });
        };

        // Update Mata Kuliah
        window.updateMatakuliah = function() {
            const form = document.getElementById('editMatkulForm');
            const kodemk = form.querySelector('input[name="edit_kodemk"]').value;
            const formData = new FormData(form);
            const data = {};
            formData.forEach((value, key) => data[key] = value);

            fetch(`/kaprodi/matkul/update/${kodemk}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editMatkulModal'));
                    if (data.status === 'success') {
                        alert(data.message);
                        modal.hide();
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memperbarui data');
                });
        };

        // Delete Mata Kuliah
        window.deleteMatkul = function(kodemk) {
            if (confirm('Apakah Anda yakin ingin menghapus mata kuliah ini?')) {
                fetch(`/kaprodi/matkul/delete/${kodemk}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menghapus data');
                    });
            }
        };

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchText = this.value.toLowerCase();
                const tableRows = document.querySelectorAll('tbody tr');

                tableRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchText) ? '' : 'none';
                });
            });
        }

        // Refresh table
        const refreshButton = document.getElementById('refreshTable');
        if (refreshButton) {
            refreshButton.addEventListener('click', function() {
                location.reload();
            });
        }
    });
</script>