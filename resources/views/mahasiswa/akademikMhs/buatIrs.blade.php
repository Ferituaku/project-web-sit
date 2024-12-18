{{-- akademisi.blade.php --}}
@extends('mahasiswa.akademikMhs.akademik-base')
@section('akademik-content')

<div class="container-fluid py-4">

    {{-- Alert for messages --}}
    @if(session('success') || session('error'))
    <div class="alert alert-{{ session('success') ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
        {{ session('success') ?? session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    {{-- Kondisional untuk IRS yang sudah disetujui atau tidak ada periode IRS --}}
    @if(isset($existingIrs) && $existingIrs->approval === '1')
    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <div class="mb-4">
                <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
            </div>
            <div class="mb-4">
                <h3 class="mb-3">IRS Telah Disetujui</h3>
                <p class="text-muted">
                    IRS Anda untuk Semester {{ $existingIrs->semester }}
                    Tahun Ajaran {{ $existingIrs->tahun_ajaran }} telah disetujui.
                </p>
                <p class="text-muted">
                    Total SKS: <span class="fw-bold">{{ $existingIrs->total_sks }}</span>
                </p>
            </div>
            @if(isset($canCancel) && $canCancel)
            <!-- <div class="mt-4">
                <button class="btn btn-danger" onclick="cancelIRS({{ $existingIrs->id }})">
                    <i class="fas fa-times-circle me-2"></i>Batalkan IRS
                </button>
            </div> -->
            <small class="text-muted mt-2 mb-4">
                *Pembatalan IRS hanya dapat dilakukan dalam 4 minggu setelah persetujuan

            </small>
            @endif
            <!-- <div class="mb-4">
                <p class="text-secondary">
                    <i class="fas fa-info-circle me-2"></i>
                    IRS yang telah disetujui tidak dapat diubah.
                </p>
            </div> -->

            <div class="d-flex justify-content-center gap-3 mt-2">
                <a href="{{ route('mahasiswa.akademikMhs.hasilirs') }}" class="btn btn-primary">
                    <i class="fas fa-eye me-2"></i>Lihat Detail IRS
                </a>
            </div>
        </div>
    </div>
    @elseif(isset($periodExpired) && $periodExpired)
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Periode perubahan IRS telah berakhir (2 minggu setelah pengisian)
    </div>
    @else
    <div class="row">
        <!-- Left Panel - Course Selection -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Pemilihan Mata Kuliah</h5>
                </div>
                <div class="card-body">
                    <!-- SKS Counter -->
                    <div class="mb-3">
                        <h5>Total SKS: <span id="total-sks">0</span>/24</h5>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 0%;"
                                aria-valuenow="0" aria-valuemin="0" aria-valuemax="24">
                            </div>
                        </div>
                    </div>

                    <!-- Filter Controls -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <select class="form-select" id="semester-filter">
                                <option value="">Pilih Semester</option>
                                @for($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}" {{ request('semester') == $i ? 'selected' : '' }}>
                                    Semester {{ $i }}
                                    </option>
                                    @endfor
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="search-course" class="form-control"
                                placeholder="Cari mata kuliah...">
                        </div>
                    </div>

                    <!-- Available Courses List -->
                    <div id="available-courses" class="mt-4">
                        <h5 class="mb-3">Mata Kuliah Tersedia</h5>
                        <div id="available-courses-list" class="list-group">
                            @foreach($jadwalKuliah as $jadwal)
                            <div class="list-group-item course-item" data-jadwal-id="{{ $jadwal->id }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <button class="btn btn-info btn-sm toggle-schedule"
                                        data-jadwal-id="{{ $jadwal->id }}"
                                        data-visible="false">
                                        <i class="bi bi-eye" style="font-size: 1.2rem;"></i>
                                    </button>
                                    <div class="ms-3">
                                        <h6 class="mb-1">{{ $jadwal->matakuliah->nama_mk }}</h6>
                                        <small>Kode: {{ $jadwal->kodemk }}</small>
                                        <small class="d-block">Semester {{ $jadwal->plot_semester }}</small>
                                        <small class="d-block">{{ $jadwal->hari }}, {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</small>
                                    </div>
                                    <span class="badge bg-primary rounded-pill">{{ $jadwal->matakuliah->sks }} SKS</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Right Panel - Schedule -->
        <div class="col-md-8 ">

            <div class="card shadow-sm ">
                <div class="card-header bg-white d-grid gap-2 d-md-flex justify-content-between">
                    <h5 class="card-title mb-0">Jadwal Kuliah</h5>
                    <button type="button" class="btn btn-primary me-md-2" data-bs-toggle="modal" data-bs-target="#selectedCoursesModal">
                        <i class="fas fa-list me-2"></i>Lihat Mata Kuliah Terpilih
                        <span class="badge bg-white text-primary ms-2" id="selected-courses-count">0</span>
                    </button>
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
                                            role="button"
                                            onclick="handleScheduleItemClick(this)">
                                            <div class="card-body p-2">
                                                <small class="d-block fw-bold">{{ $jadwal->matakuliah->nama_mk }}</small>
                                                <small class="d-block">Kode: {{ $jadwal->kodemk }}</small>
                                                <small class="d-block">Kelas: {{ $jadwal->class_group }}</small>
                                                <small class="d-block">SMT({{ $jadwal->plot_semester }})</small>
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
    @endif
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

<!-- Confirm Delete Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus mata kuliah ini dari IRS?</p>
                <p id="course-to-delete-info"></p>
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
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <i id="alert-icon" class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                </div>
                <h4 id="alert-message" class="mb-0"></h4>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page-style')
@if(!isset($existingIrs) || $existingIrs->approval !== '1')
<style>
    .schedule-card {
        transition: transform 0.2s ease;
    }

    .schedule-card:hover {
        transform: scale(1.02);
        z-index: 1;
    }

    .course-item {
        transition: all 0.3s ease;
    }

    .course-item:hover {
        background-color: #f8f9fa;
    }

    .toggle-schedule {
        min-width: 60px;
    }

    .schedule-item {
        transition: all 0.3s ease;
        display: none;
        /* Hidden by default */
    }

    .schedule-item.visible {
        display: block;
    }
</style>
@endif
@endsection


@section('page-scripts')
@if(!isset($existingIrs) || $existingIrs->approval !== '1')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // State management
        let selectedCourses = new Map();
        const MAX_SKS = 24;
        let currentTotalSks = 0;

        // Initialize modals
        const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        const alertModal = new bootstrap.Modal(document.getElementById('alertModal'));

        // Initialize from existing selections
        document.querySelectorAll('.schedule-item.border-success').forEach(item => {
            const jadwalId = item.dataset.jadwalId;
            const sks = parseInt(item.dataset.sks);
            selectedCourses.set(jadwalId, {
                id: jadwalId,
                sks: sks,
                element: item
            });
            currentTotalSks += sks;
            updateDisplay();
        });

        // Course selection handler
        window.handleScheduleItemClick = function(element) {
            const jadwalId = element.dataset.jadwalId;
            const sks = parseInt(element.dataset.sks);

            if (element.classList.contains('border-success')) {
                showDeleteConfirmation(jadwalId, sks, element);
            } else {
                if (!validateScheduleConflict(element)) return;
                if (currentTotalSks + sks > MAX_SKS) {
                    showAlert('Total SKS melebihi batas maksimum 24 SKS', 'warning');
                    return;
                }

                addCourse(jadwalId, sks, element);
            }
        };

        // Course management functions
        function addCourse(jadwalId, sks, element) {
            // Check if course already selected
            if (selectedCourses.has(jadwalId)) return;

            selectedCourses.set(jadwalId, {
                id: jadwalId,
                sks: sks,
                element: element
            });
            currentTotalSks += sks;
            element.classList.replace('border-info', 'border-success');
            updateDisplay();
            console.log('Added course:', jadwalId, 'Total courses:', selectedCourses.size);
        }

        function removeCourse(jadwalId) {
            const course = selectedCourses.get(jadwalId);
            if (!course) return;

            currentTotalSks -= course.sks;
            course.element.classList.replace('border-success', 'border-info');
            selectedCourses.delete(jadwalId);
            updateDisplay();
            console.log('Removed course:', jadwalId, 'Total courses:', selectedCourses.size);
        }

        function showDeleteConfirmation(jadwalId, sks, element) {
            const courseInfo = extractCourseInfo(element);
            document.getElementById('course-to-delete-info').textContent =
                `${courseInfo.name} (${courseInfo.code})`;

            const confirmDeleteBtn = document.getElementById('confirm-delete');
            confirmDeleteBtn.onclick = () => {
                removeCourse(jadwalId);
                confirmDeleteModal.hide();
            };

            confirmDeleteModal.show();
        }

        function timeToMinutes(timeString) {
            const [hours, minutes] = timeString.split(':').map(Number);
            return hours * 60 + minutes;
        }

        function validateScheduleConflict(newElement) {
            const newSchedule = extractScheduleInfo(newElement);
            const newCourseInfo = extractCourseInfo(newElement);

            // Check for duplicate courses first
            for (const [_, course] of selectedCourses) {
                const existingCourseInfo = extractCourseInfo(course.element);

                // Check if the same course code is already selected
                if (existingCourseInfo.code === newCourseInfo.code) {
                    showAlert(`Mata kuliah ${newCourseInfo.name} (${newCourseInfo.code}) sudah dipilih`, 'warning');
                    return false;
                }

                // Check time conflict
                const existingSchedule = extractScheduleInfo(course.element);
                if (hasTimeConflict(newSchedule, existingSchedule)) {
                    showAlert(`Jadwal bertabrakan dengan mata kuliah ${existingCourseInfo.name}`, 'warning');
                    return false;
                }
            }
            return true;
        }

        function hasTimeConflict(schedule1, schedule2) {
            if (schedule1.day !== schedule2.day) return false;

            const start1 = timeToMinutes(schedule1.startTime);
            const end1 = timeToMinutes(schedule1.endTime);
            const start2 = timeToMinutes(schedule2.startTime);
            const end2 = timeToMinutes(schedule2.endTime);

            return (start1 < end2 && start2 < end1);
        }

        // UI update functions
        function updateDisplay() {
            updateSksCounter();
            updateSelectedCoursesList();
        }

        function updateSksCounter() {
            const totalSksElements = document.querySelectorAll('#total-sks');
            const progressBars = document.querySelectorAll('.progress-bar');

            totalSksElements.forEach(element => {
                element.textContent = currentTotalSks;
            });

            const progressPercentage = (currentTotalSks / MAX_SKS) * 100;
            progressBars.forEach(bar => {
                bar.style.width = `${progressPercentage}%`;
                bar.setAttribute('aria-valuenow', currentTotalSks);
                bar.className = 'progress-bar';
                if (progressPercentage > 100) {
                    bar.classList.add('bg-danger');
                } else if (progressPercentage >= 75) {
                    bar.classList.add('bg-warning');
                } else {
                    bar.classList.add('bg-success');
                }
            });
        }

        function updateSelectedCoursesList() {
            const tableBody = document.getElementById('selected-courses-table');
            const coursesCount = document.getElementById('selected-courses-count');

            // Clear existing content
            tableBody.innerHTML = '';

            // Update count
            coursesCount.textContent = selectedCourses.size;

            // Add each selected course to the table
            selectedCourses.forEach((course, jadwalId) => {
                const courseInfo = extractCourseInfo(course.element);
                const scheduleInfo = extractScheduleInfo(course.element);

                const row = document.createElement('tr');
                row.innerHTML = `
                <td>${courseInfo.code}</td>
                <td>${courseInfo.name}</td>
                <td>${course.sks}</td>
                <td>${courseInfo.group}</td>
                <td>${scheduleInfo.day} ${scheduleInfo.startTime}-${scheduleInfo.endTime}</td>
                <td>
                    <button class="btn btn-sm btn-danger remove-course" data-jadwal-id="${jadwalId}">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            `;
                tableBody.appendChild(row);
            });

            // Add event listeners to remove buttons
            document.querySelectorAll('.remove-course').forEach(button => {
                button.addEventListener('click', function() {
                    const jadwalId = this.dataset.jadwalId;
                    const course = selectedCourses.get(jadwalId);
                    if (course) {
                        showDeleteConfirmation(jadwalId, course.sks, course.element);
                    }
                });
            });
        }

        function extractCourseInfo(element) {
            const cardBody = element.querySelector('.card-body');
            return {
                name: cardBody.querySelector('small:nth-child(1)').textContent.trim(),
                // Get clean course code by removing 'Kode: ' prefix
                code: cardBody.querySelector('small:nth-child(2)').textContent.replace('Kode:', '').trim(),
                group: cardBody.querySelector('small:nth-child(3)').textContent.replace('Kelas:', '').trim()
            };
        }

        function extractScheduleInfo(element) {
            const cardBody = element.querySelector('.card-body');
            const timeText = cardBody.querySelector('small:last-child').textContent.trim();
            const [startTime, endTime] = timeText.split(' - ');

            return {
                day: element.closest('td').dataset.day,
                startTime: startTime,
                endTime: endTime
            };
        }

        function showAlert(message, type = 'info') {
            const alertModal = document.getElementById('alertModal');
            const alertMessage = alertModal.querySelector('#alert-message');
            const alertIcon = alertModal.querySelector('#alert-icon');

            alertMessage.textContent = message;

            alertIcon.className = 'fas fa-3x mb-3';
            switch (type) {
                case 'success':
                    alertIcon.classList.add('fa-check-circle', 'text-success');
                    break;
                case 'warning':
                    alertIcon.classList.add('fa-exclamation-triangle', 'text-warning');
                    break;
                case 'error':
                    alertIcon.classList.add('fa-times-circle', 'text-danger');
                    break;
                default:
                    alertIcon.classList.add('fa-info-circle', 'text-info');
            }

            new bootstrap.Modal(alertModal).show();
        }

        // Save IRS handler
        document.getElementById('save-irs').addEventListener('click', async function() {
            if (selectedCourses.size === 0) {
                showAlert('Pilih minimal satu mata kuliah', 'warning');
                return;
            }

            try {
                const response = await fetch('/mahasiswa/akademikMhs/save-irs', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        jadwals: Array.from(selectedCourses.keys()),
                    })
                });

                const data = await response.json();
                if (data.success) {
                    showAlert(data.message, 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showAlert(data.message || 'Gagal menyimpan IRS', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan saat menyimpan IRS', 'error');
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi toggle buttons
        const toggleButtons = document.querySelectorAll('.toggle-schedule');

        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const jadwalId = this.dataset.jadwalId;
                const isVisible = this.dataset.visible === 'true';

                // Toggle visibility state
                this.dataset.visible = (!isVisible).toString();

                // Toggle icon
                const icon = this.querySelector('i');
                if (isVisible) {
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {

                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
                // Toggle visibility di schedule matrix
                const scheduleItems = document.querySelectorAll(`.schedule-item[data-jadwal-id="${jadwalId}"]`);
                scheduleItems.forEach(item => {
                    if (isVisible) {
                        item.style.display = 'none';
                    } else {
                        item.style.display = 'block';
                    }
                });
            });
        });

        // Filter functionality
        const semesterFilter = document.getElementById('semester-filter');
        const searchInput = document.getElementById('search-course');

        function filterCourses() {
            const semester = semesterFilter.value;
            const searchTerm = searchInput.value.toLowerCase();

            document.querySelectorAll('.course-item').forEach(item => {
                const courseInfo = item.querySelector('h6').textContent.toLowerCase();
                const courseSemester = item.querySelector('small:nth-child(3)').textContent.match(/\d+/)[0];

                const matchesSemester = !semester || courseSemester === semester;
                const matchesSearch = !searchTerm || courseInfo.includes(searchTerm);

                item.style.display = matchesSemester && matchesSearch ? 'block' : 'none';
            });
        }

        semesterFilter.addEventListener('change', filterCourses);
        searchInput.addEventListener('input', filterCourses);
    });
</script>
@endif
@endsection