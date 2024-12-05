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
                            <th>SKS</th>
                            <th>Semester
                                <button id="sortSemester" class="btn btn-sm p-0" title="Urutkan Semester">
                                    <i id="sortIcon" class="bi bi-sort-down"></i>
                                </button>
                            </th>
                            <th>Kelas</th>
                            <th>Hari</th>
                            <th>Waktu</th>
                            <th>Ruangan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jadwalKuliah as $index => $jadwal)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $jadwal->matakuliah->kodemk }}</td>
                            <td>{{ $jadwal->matakuliah->nama_mk }}</td>
                            <td>{{ $jadwal->pembimbingakd->name }}</td>
                            <td>{{$jadwal->matakuliah->sks}}</td>
                            <td>{{ $jadwal->plot_semester }}</td>
                            <td>{{ $jadwal->class_group }}</td>
                            <td>{{ $jadwal->hari }}</td>
                            <td>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                            <td>{{ $jadwal->ruangKelas->koderuang }}</td>
                            <td>
                                @if($jadwal->approval == '0')
                                <span class="badge bg-warning">Pending</span>
                                @elseif($jadwal->approval == '1')
                                <span class="badge bg-success">Disetujui</span>
                                @else
                                <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                @if($jadwal->approval == '0' || $jadwal->approval == '2')
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
                                @else
                                <div class="text-muted text-sm">
                                    Sudah disetujui
                                </div>
                                @endif
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
            <form action="{{ route('kaprodi.jadwal.store') }}" method="POST" id="jadwalForm">
                @csrf
                <div class="modal-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Basic Information -->
                    <div class="row mb-3">
                        <input type="hidden" name="prodi_id" value="{{ DB::table('pembimbingakd')->where('nip', Auth::user()->nip)->value('prodi_id') }}">
                        <div class="col-md-6">
                            <label class="form-label">Mata Kuliah</label>
                            <select class="form-select @error('kodemk') is-invalid @enderror"
                                name="kodemk" id="kodemk" required>
                                <option value="">Pilih Mata Kuliah</option>
                                @foreach($matakuliah as $mk)
                                <option value="{{ $mk->kodemk }}" data-sks="{{ $mk->sks }}" data-semester="{{ $mk->semester }}">
                                    {{ $mk->kodemk }} - {{ $mk->nama_mk }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Dosen</label>
                            <select class="form-select" name="dosen_id" required>
                                <option value="">Pilih Dosen</option>
                                @foreach($dosen as $d)
                                <option value="{{ $d->nip }}">{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">SKS</label>
                            <input type="number" class="form-control" id="sks" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Plot Semester</label>
                            <input type="number" class="form-control" id="plot_semester" name="plot_semester" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jumlah Kelas</label>
                            <input type="number" class="form-control" name="group_count" id="groupCount" required min="1">
                        </div>
                    </div>

                    <!-- Dynamic Class Groups -->
                    <div id="classGroups">
                        <!-- Class group sections will be added here dynamically -->
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


<!-- Edit Schedule Modal -->
<div class="modal fade" id="editScheduleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Jadwal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form {{ route('kaprodi.jadwal.update', ['id' => $jadwal->id]) }}" method="POST" id="editScheduleForm">
                @csrf
                @method('GET')
                <div class="modal-body">
                    <!-- Error Section -->
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <input type="hidden" name="jadwal_id" id="editJadwalId">

                    <!-- Form Fields -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Mata Kuliah</label>
                            <select class="form-select" name="kodemk" id="editKodemk" required>
                                @foreach($matakuliah as $mk)
                                <option value="{{ $mk->kodemk }}" data-sks="{{ $mk->sks }}" data-semester="{{ $mk->semester }}">
                                    {{ $mk->kodemk }} - {{ $mk->nama_mk }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Dosen</label>
                            <select class="form-select" name="dosen_id" id="editDosenId" required>
                                @foreach($dosen as $d)
                                <option value="{{ $d->nip }}">{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Hari</label>
                            <select class="form-select" name="hari" id="editHari" required>
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                                <option value="{{ $hari }}">{{ $hari }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jam Mulai</label>
                            <input type="time" class="form-control" name="jam_mulai" id="editJamMulai" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Ruangan</label>
                            <select class="form-select" name="ruangkelas_id" id="editRuangKelas" required>
                                @foreach($ruangKelas as $ruang)
                                <option value="{{ $ruang->koderuang }}">{{ $ruang->koderuang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Plot Semester</label>
                            <input type="number" class="form-control" name="plot_semester" id="editSemester" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" onclick="updateJadwal()">Simpan Perubahan</button>
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

    .class-group-section {
        border: 1px solid #dee2e6;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 5px;
    }
</style>
@endsection

@section('scriptKpd')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // menyimpan rejection reason modal
        rejectionModal = new bootstrap.Modal(document.getElementById('rejectionReasonModal'));

        // Flag untuk melacak arah urutan: true = ascending, false = descending
        let isAscending = true;
        // Fungsi untuk mengurutkan tabel berdasarkan kolom semester
        function sortTableBySemester() {
            const tbody = document.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));

            rows.sort((a, b) => {
                const semesterA = parseInt(a.querySelector('td:nth-child(5)').textContent);
                const semesterB = parseInt(b.querySelector('td:nth-child(5)').textContent);
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


        const groupCountSelect = document.getElementById('groupCount');
        const classGroupsContainer = document.getElementById('classGroups');

        function createClassGroupSection(groupNumber) {
            const groupLetter = String.fromCharCode(65 + groupNumber - 1); // Convert 1 to A, 2 to B, etc.
            return `
                <div class="class-group-section mb-3">
                    <h6 class="mb-3">Kelas ${groupLetter}</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Hari</label>
                            <select class="form-select" name="hari_${groupLetter}" required>
                                <option value="">Pilih Hari</option>
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                                <option value="{{ $hari }}">{{ $hari }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jam Mulai</label>
                            <select class="form-select" name="jam_mulai_${groupLetter}" required>
                                <option value="">Pilih Jam</option>
                                @foreach($timeSlots as $time)
                                <option value="{{ $time }}">{{ $time }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ruang Kelas</label>
                            <select class="form-select @error('ruangkelas_id') is-invalid @enderror" name="ruangkelas_id_${groupLetter}" required>
                                <option value="">Pilih Ruangan</option>
                                
                                    @foreach($ruangKelas as $ruang)
                                    @php
                                    $programStudiNama = $program_studi->firstWhere('id', $ruang->program_studi_id)->nama ?? 'Tidak Ditemukan';
                                    @endphp
                                    <option value="{{ $ruang->koderuang }}">{{ $ruang->koderuang }} - {{$programStudiNama }}</option>
                                    @endforeach
                            </select>
                            @error('ruangkelas_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            `;
        }

        // grup kelas update
        function updateClassGroups() {
            const count = parseInt(groupCountSelect.value);
            classGroupsContainer.innerHTML = '';
            for (let i = 1; i <= count; i++) {
                classGroupsContainer.innerHTML += createClassGroupSection(i);
            }
        }

        groupCountSelect.addEventListener('change', updateClassGroups);
        updateClassGroups(); // Initial creation

        // isi otomatis sks sesuai database MK
        const kodeMkSelect = document.getElementById('kodemk');
        const sksInput = document.getElementById('sks');
        const plotSemesterInput = document.getElementById('plot_semester');

        kodeMkSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const sks = selectedOption.getAttribute('data-sks');
            const semester = selectedOption.getAttribute('data-semester');

            sksInput.value = sks || '';
            plotSemesterInput.value = semester || '';
        });

        // fungsi Search 
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

        function editJadwal(id) {
            // Fetch data berdasarkan ID jadwal

            const jadwal = JSON.parse('<?php echo json_encode($jadwalKuliah); ?>').find(j => j.id === id);

            if (jadwal) {
                document.getElementById('editJadwalId').value = jadwal.id;
                document.getElementById('editKodemk').value = jadwal.matakuliah.kodemk;
                document.getElementById('editDosenId').value = jadwal.pembimbingakd.nip;
                document.getElementById('editHari').value = jadwal.hari;
                document.getElementById('editJamMulai').value = jadwal.jam_mulai;
                document.getElementById('editJamSelesai').value = jadwal.jam_selesai;
                document.getElementById('editRuangKelas').value = jadwal.ruangKelas.koderuang;
                document.getElementById('editSemester').value = jadwal.plot_semester;
            }
        }

        function showRejectionReason(button) {
            const reason = button.getAttribute('data-reason');

            document.getElementById('rejectionReason').textContent = `<p>${reason}</p>`;
            // Set alasan penolakan
            document.getElementById('rejectionReason').textContent = reason;

            // Tampilkan modal
            rejectionModal.show();
        }

        // Delete confirmation
        window.confirmDelete = function(id) {
            if (confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) {
                window.location.href = `/kaprodi/jadwal/delete/${id}`;
            }
        };
    });
</script>
@endsection