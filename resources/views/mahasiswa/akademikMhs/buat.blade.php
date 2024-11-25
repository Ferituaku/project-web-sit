@extends('mahasiswa.akademikMhs.akademik-base')
@section('akademik-content')

<div class="container-fluid py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('mahasiswa.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Buat IRS</li>
        </ol>
    </nav>

    @if(session('success') || session('error'))
    <div class="alert alert-{{ session('success') ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
        {{ session('success') ?? session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="col-md-4">
                            <select class="form-select" id="semester-filter">
                                <option value="">Pilih Semester</option>
                                @for($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}" {{ request('semester') == $i ? 'selected' : '' }}>
                                    Semester {{ $i }}
                                    </option>
                                    @endfor
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#selectedCoursesModal">
                            <i class="fas fa-list me-2"></i>Lihat Mata Kuliah Terpilih
                            <span class="badge bg-white text-primary ms-2" id="selected-courses-count">0</span>
                        </button>
                    </div>

                    <div id="available-courses" class="mt-4" style="display: none;">
                        <h5 class="mb-3">Mata Kuliah Tersedia</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
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
                                <tbody id="available-courses-list">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule Panel -->
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Jadwal Kuliah</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 100px;">Jam</th>
                                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $day)
                                    <th class="text-center">{{ $day }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($timeSlots as $time)
                                <tr>
                                    <td class="text-center">{{ $time }}</td>
                                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $day)
                                    <td class="schedule-cell p-1" data-day="{{ $day }}" data-time="{{ $time }}">
                                        @foreach($scheduleMatrix[$time][$day] as $jadwal)
                                        <div class="schedule-item card {{ in_array($jadwal->id, $selectedJadwalIds ?? []) ? 'border-success' : 'border-info' }} mb-1"
                                            data-jadwal-id="{{ $jadwal->id }}"
                                            data-sks="{{ $jadwal->matakuliah->sks }}"
                                            data-semester="{{ $jadwal->plot_semester }}">
                                            <div class="card-body p-2">
                                                <small class="d-block fw-bold">{{ $jadwal->matakuliah->nama_mk }}</small>
                                                <small class="d-block">Kode: {{ $jadwal->kodemk }}</small>
                                                <small class="d-block">Kelas: {{ $jadwal->class_group }}</small>
                                                <small class="d-block">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</small>
                                            </div>
                                        </div>
                                        @endforeach
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Selected Courses Modal -->
<div class="modal fade" id="selectedCoursesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mata Kuliah Terpilih</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="save-irs">Simpan IRS</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus mata kuliah ini dari IRS?</p>
                <p class="mb-0 fw-bold" id="delete-course-name"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">Hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- Alert Modal -->
<div class="modal fade" id="alertModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <i id="alert-icon" class="fas fa-3x mb-3"></i>
                </div>
                <h5 id="alert-message" class="mb-0"></h5>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page-style')
<style>
    .schedule-item {
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .schedule-item:hover {
        transform: scale(1.02);
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .schedule-item.border-success {
        background-color: rgba(25, 135, 84, 0.1);
    }

    .progress-bar {
        transition: width 0.3s ease;
    }

    #available-courses {
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection

@section('page-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
                let selectedCourses = new Map();
                const MAX_SKS = 24;
                let currentTotalSks = 0;
                let deleteJadwalId = null;

                // Initialize from existing selections
                document.querySelectorAll('.schedule-item.border-success').forEach(item => {
                    const jadwalId = item.dataset.jadwalId;
                    const sks = parseInt(item.dataset.sks);
                    addCourse(item);
                });

                // Semester filter change handler
                document.getElementById('semester-filter').addEventListener('change', function() {
                    const semester = this.value;
                    const availableCoursesDiv = document.getElementById('available-courses');
                    const scheduleItems = document.querySelectorAll('.schedule-item');

                    if (semester) {
                        // Show/hide relevant schedule items
                        scheduleItems.forEach(item => {
                            if (item.dataset.semester === semester) {
                                item.style.display = 'block';
                            } else {
                                item.style.display = 'none';
                            }
                        });

                        // Populate available courses
                        const availableCoursesList = document.getElementById('available-courses-list');
                        availableCoursesList.innerHTML = '';

                        scheduleItems.forEach(item => {
                            if (item.dataset.semester === semester) {
                                const tr = document.createElement('tr');
                                const courseInfo = item.querySelector('.card-body').cloneNode(true);

                                tr.innerHTML = `
                        <li>${courseInfo.querySelector('small:nth-child(2)').textContent.replace('Kode: ', '')}</li>
                        <li>${courseInfo.querySelector('small:nth-child(1)').textContent}</li>
                        <li>${item.dataset.sks}</li>
                        <li>${courseInfo.querySelector('small:nth-child(3)').textContent.replace('Kelas: ', '')}</li>
                        <li>${courseInfo.querySelector('small:nth-child(4)').textContent}</li>
                        <li>
                            <button class="btn btn-sm btn-primary select-course" data-jadwal-id="${item.dataset.jadwalId}">
                                Pilih
                            </button>
                        </li>
                    `;
                                availableCoursesList.appendChild(tr);
                            }
                        });

                        availableCoursesDiv.style.display = 'block';
                    } else {
                        availableCoursesDiv.style.display = 'none';
                        scheduleItems.forEach(item => {
                            item.style.display = 'block';
                        });
                    }
                });

                // Schedule item click handler
                document.querySelectorAll('.schedule-item').forEach(item => {
                    item.addEventListener('click', function() {
                        const jadwalId = this.dataset.jadwalId;
                        const sks = parseInt(this.dataset.sks);

                        if (this.classList.contains('border-success')) {
                            showDeleteConfirmation(this);
                        } else {
                            if (checkAndAddCourse(this)) {
                                this.classList.replace('border-info', 'border-success');
                            }
                        }
                    });
                });

                // Available courses select button handler
                document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('select-course')) {
                        const jadwalId = e.target.dataset.jadwalId;
                        const scheduleItem = document.querySelector(`.schedule-item[data-jadwal-id="${jadwalId}"]`);

                        if (checkAndAddCourse(scheduleItem)) {
                            scheduleItem.classList.replace('border-info', 'border-success');
                            e.target.disabled = true;
                            e.target.textContent = 'Terpilih';
                        }
                    }
                });

                // Delete confirmation handlers
                document.getElementById('confirm-delete').addEventListener('click', function() {
                    if (deleteJadwalId) {
                        const scheduleItem = document.querySelector(`.schedule-item[data-jadwal-id="${deleteJadwalId}"]`);
                        removeCourse(scheduleItem);
                        deleteJadwalId = null;
                        bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal')).hide();
                    }
                });

                // Save IRS handler
                document.getElementById('save-irs').addEventListener('click', function() {
                            if (selectedCourses.size === 0) {
                                showAlert('Pilih minimal satu mata kuliah', 'warning');
                                return;
                            }

                            const jadwalIds = Array.from(selectedCourses.keys());

                            fetch('/mahasiswa/akademikMhs/save-irs', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                    },
                                    body: JSON.stringify({
                                        jadwals: jadwalIds
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        showAlert(data.message, 'success');
                                        setTimeout(() => window.location.reload(), 1500);
                                    } else {
                                        showAlert(data.message, 'error');
                                    }
                                })
                                .catch(error => {
                                        showAlert('Terjadi kesalahan s 