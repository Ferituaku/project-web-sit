<!-- buatjadwal.blade.php -->
@extends('kaprodi.mainKpd')
@section('title', 'Manajemen Jadwal')

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
                    <h5 class="mb-0 text-dark">Data Jadwal Perkuliahan</h5>
                </div>
                <div class="col-md-6 text-md-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Jadwal
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
                            <th>Mata Kuliah</th>
                            <th>Dosen</th>
                            <th>Semester</th>
                            <th>Hari</th>
                            <th>Waktu</th>
                            <th>Ruangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jadwalKuliah as $index => $jadwal)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $jadwal->matakuliah->kodemk }}</td>
                            <td>{{ $jadwal->matakuliah->nama_mk }}</td>
                            <td>{{ $jadwal->dosen->name }}</td>
                            <td>{{ $jadwal->plot_semester }}</td>
                            <td>{{ $jadwal->hari }}</td>
                            <td>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                            <td>{{ $jadwal->ruangKelas->koderuang }}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-info me-1"
                                        onclick="editJadwal({{ $jadwal->id }})"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editScheduleModal">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger"
                                        onclick="confirmDelete({{ $jadwal->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Schedule Modal -->
<div class="modal fade" id="addScheduleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Jadwal Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('kaprodi.jadwal.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Mata Kuliah</label>
                            <select class="form-select" name="kodemk" id="kodemk" required>
                                <option value="">Pilih Mata Kuliah</option>
                                @foreach($matakuliah as $mk)
                                <option value="{{ $mk->kodemk }}" data-sks="{{ $mk->sks }}">
                                    {{ $mk->kodemk }} - {{ $mk->nama_mk }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ruang Kelas</label>
                            <select class="form-select @error('ruangkelas_id') is-invalid @enderror" name="ruangkelas_id" required>
                                <option value="">Pilih Ruangan</option>
                                @foreach($ruangKelas as $ruang)
                                <option value="{{ $ruang->koderuang }}">{{ $ruang->koderuang }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Dosen</label>
                            <select class="form-select" name="dosen_id" required>
                                <option value="">Pilih Dosen</option>
                                @foreach($dosen as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Plot Semester</label>
                            <select class="form-select" name="plot_semester" required>
                                <option value="">Pilih Semester</option>
                                @for($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}">Semester {{ $i }}</option>
                                    @endfor
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Hari</label>
                            <select class="form-select" name="hari" required>
                                <option value="">Pilih Hari</option>
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                                <option value="{{ $hari }}">{{ $hari }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">SKS</label>
                            <input type="number" class="form-control" id="sks" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jam Mulai</label>
                            <select class="form-select" name="jam_mulai" required>
                                <option value="">Pilih Jam</option>
                                @foreach($timeSlots as $time)
                                <option value="{{ $time }}">{{ $time }}</option>
                                @endforeach
                            </select>
                            @error('jam_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
    .table th {
        font-weight: 600;
        background-color: #f8f9fa;
    }

    .modal-lg {
        max-width: 800px;
    }

    .form-label {
        font-weight: 500;
    }
</style>
@endsection

@section('scriptKpd')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-fill SKS when mata kuliah is selected
        const kodeMkSelect = document.getElementById('kodemk');
        const sksInput = document.getElementById('sks');

        kodeMkSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const sks = selectedOption.getAttribute('data-sks');
            sksInput.value = sks || '';
        });

        // Delete confirmation
        window.confirmDelete = function(id) {
            if (confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) {
                window.location.href = `/kaprodi/jadwal/delete/${id}`;
            }
        };

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('tbody tr');

            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Semester filter
        const semesterFilter = document.getElementById('semester-filter');
        semesterFilter.addEventListener('change', function() {
            const semester = this.value;
            const tableRows = document.querySelectorAll('tbody tr');

            tableRows.forEach(row => {
                const semesterCell = row.querySelector('td:nth-child(5)');
                if (!semester || semesterCell.textContent === semester) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection