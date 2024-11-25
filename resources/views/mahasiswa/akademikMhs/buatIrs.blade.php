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

                    <div id="available-courses" class="mt-4" style="display: none;">
                        <h5 class="mb-3">Mata Kuliah Tersedia</h5>
                        <div class="table-responsive">
                            <div id="selected-courses-list" class="list-group">
                                <div id="available-courses-list">
                                </div>
                            </div>
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
                                    <td class="schedule-cell p-1"
                                        data-day="{{ $day }}"
                                        data-time="{{ $time }}">
                                        @foreach($scheduleMatrix[$time][$day] as $jadwal)
                                        <div class="schedule-item card {{ in_array($jadwal->id, $selectedJadwalIds ?? []) ? 'border-success' : 'border-info' }} mb-1"
                                            data-jadwal-id="{{ $jadwal->id }}"
                                            data-sks="{{ $jadwal->matakuliah->sks }}">
                                            <div class="card-body p-2">
                                                <small class="d-block fw-bold">
                                                    {{ $jadwal->matakuliah->nama_mk }}
                                                </small>
                                                <small class="d-block">
                                                    Kode: {{ $jadwal->kodemk }}
                                                </small>
                                                <small class="d-block">
                                                    Kelas: {{ $jadwal->class_group }}
                                                </small>
                                                <small class="d-block">
                                                    {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                                                </small>
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
<style>
    .schedule-card {
        transition: transform 0.2s ease;
    }

    .schedule-card:hover {
        transform: scale(1.02);
        z-index: 1;
    }
</style>
@endsection

@section('page-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let selectedCourses = [];
        const MAX_SKS = 24;
        let currentTotalSks = 0;

        // Initialize from existing selections if any
        document.querySelectorAll('.schedule-item.border-success').forEach(item => {
            const jadwalId = item.dataset.jadwalId;
            const sks = parseInt(item.dataset.sks);
            selectedCourses.push({
                id: jadwalId,
                sks: sks
            });
            currentTotalSks += sks;
            updateSksCounter();
        });


        // Handle schedule item click
        document.querySelectorAll('.schedule-item').forEach(item => {
            item.addEventListener('click', function() {
                const jadwalId = this.dataset.jadwalId;
                const sks = parseInt(this.dataset.sks);

                if (this.classList.contains('border-success')) {
                    // Remove course
                    selectedCourses = selectedCourses.filter(course => course.id !== jadwalId);
                    currentTotalSks -= sks;
                    this.classList.replace('border-success', 'border-info');
                } else {
                    // Add course
                    if (currentTotalSks + sks > MAX_SKS) {
                        showAlert('Total SKS melebihi batas maksimum 24 SKS', 'warning');
                        return;
                    }
                    selectedCourses.push({
                        id: jadwalId,
                        sks: sks
                    });
                    currentTotalSks += sks;
                    this.classList.replace('border-info', 'border-success');
                }

                updateSksCounter();
                updateSelectedCoursesList();
            });
        });

        // Save IRS
        document.getElementById('save-irs').addEventListener('click', function() {
            if (selectedCourses.length === 0) {
                showAlert('Pilih minimal satu mata kuliah', 'warning');
                return;
            }

            // Send to server
            fetch('/mahasiswa/save-irs', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        jadwals: selectedCourses.map(course => course.id)
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert(data.message, 'success');
                        // Optional: Reload page after success
                        // setTimeout(() => window.location.reload(), 1500);
                    } else {
                        showAlert(data.message, 'error');
                    }
                })
                .catch(error => {
                    showAlert('Terjadi kesalahan saat menyimpan IRS', 'error');
                    console.error('Error:', error);
                });
        });

        function updateSksCounter() {
            const totalSksElement = document.getElementById('total-sks');
            const progressBar = document.querySelector('.progress-bar');

            totalSksElement.textContent = currentTotalSks;
            const progressPercentage = (currentTotalSks / MAX_SKS) * 100;

            progressBar.style.width = `${progressPercentage}%`;
            progressBar.setAttribute('aria-valuenow', currentTotalSks);

            // Update progress bar color
            progressBar.className = 'progress-bar';
            if (progressPercentage > 100) {
                progressBar.classList.add('bg-danger');
            } else if (progressPercentage >= 75) {
                progressBar.classList.add('bg-warning');
            } else {
                progressBar.classList.add('bg-success');
            }
        }

        function updateSelectedCoursesList() {
            const listElement = document.getElementById('selected-courses-list');
            listElement.innerHTML = '';

            selectedCourses.forEach(course => {
                const scheduleItem = document.querySelector(`.schedule-item[data-jadwal-id="${course.id}"]`);
                const courseInfo = {
                    name: scheduleItem.querySelector('small:nth-child(1)').textContent.trim(),
                    code: scheduleItem.querySelector('small:nth-child(2)').textContent.trim(),
                    group: scheduleItem.querySelector('small:nth-child(3)').textContent.trim(),
                    time: scheduleItem.querySelector('small:nth-child(4)').textContent.trim()
                };

                const div = document.createElement('div');
                div.className = 'list-group-item';
                div.innerHTML = `
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${courseInfo.name}</strong><br>
                        <small>${courseInfo.code} - ${courseInfo.group}<br>${courseInfo.time}</small>
                    </div>
                    <button class="btn btn-sm btn-danger remove-course" data-jadwal-id="${course.id}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
                listElement.appendChild(div);
            });

            // Add remove course handlers
            document.querySelectorAll('.remove-course').forEach(button => {
                button.addEventListener('click', function() {
                    const jadwalId = this.dataset.jadwalId;
                    const scheduleItem = document.querySelector(`.schedule-item[data-jadwal-id="${jadwalId}"]`);
                    scheduleItem.click(); // Trigger the click event to remove the course
                });
            });
        }

        function showAlert(message, type) {
            const alertModal = document.getElementById('alertModal');
            const alertMessage = document.getElementById('alert-message');
            const alertIcon = document.getElementById('alert-icon');

            alertMessage.textContent = message;

            // Update icon and colors based on type
            alertIcon.className = 'fas fa-3x mb-3 ';
            if (type === 'success') {
                alertIcon.classList.add('fa-check-circle', 'text-success');
            } else if (type === 'warning') {
                alertIcon.classList.add('fa-exclamation-triangle', 'text-warning');
            } else {
                alertIcon.classList.add('fa-times-circle', 'text-danger');
            }

            new bootstrap.Modal(alertModal).show();
        }
    });
</script>

@endsection