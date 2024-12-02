<?php

use App\Http\Controllers\akademikControl;
use App\Http\Controllers\AkademikController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\KaprodiController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DekanController;
use App\Http\Controllers\AuthManager;
use App\Http\Controllers\PilihMenu;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

// Grup rute untuk tamu (guest)
Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [AuthManager::class, 'login'])->name('login');
    Route::post('/login', [AuthManager::class, 'loginPost'])->name('login.post');
});

// Grup rute yang memerlukan autentikasi (auth)
Route::group(['middleware' => 'auth'], function () {

    // Rute untuk mahasiswa
    Route::group(['middleware' => 'role:mahasiswa'], function () {
        Route::get('/mahasiswa/dashboard', [MahasiswaController::class, 'mahasiswa'])->name('mahasiswa.dashboard');
        Route::get('/mahasiswa/biaya', [MahasiswaController::class, 'biaya'])->name('mahasiswa.biaya');
        Route::get('/mahasiswa/jadwal', [MahasiswaController::class, 'jadwal'])->name('mahasiswa.jadwal');
        Route::get('/mahasiswa/herreg', [MahasiswaController::class, 'herreg'])->name('mahasiswa.herreg');
        Route::get('/mahasiswa/irs', [MahasiswaController::class, 'irs'])->name('mahasiswa.irs');
        Route::get('/mahasiswa/akademikMhs/akademik-base', [MahasiswaController::class, 'akademik'])->name('mahasiswa.akademikMhs.akademik-base');
        Route::get('/mahasiswa/akademikMhs/get-courses', [MahasiswaController::class, 'getCourses'])->name('mahasiswa.akademikMhs.getCourses');

        Route::get('/mahasiswa/akademikMhs/buatIrs', [MahasiswaController::class, 'buatIrs'])->name('mahasiswa.akademikMhs.buatIrs');

        Route::post('/mahasiswa/akademikMhs/save-irs', [MahasiswaController::class, 'saveIrs'])->name('mahasiswa.akademikMhs.saveIrs');
        Route::get('/mahasiswa/akademikMhs/hasilirs', [MahasiswaController::class, 'hasilirs'])->name('mahasiswa.akademikMhs.hasilirs');
        Route::get('/mahasiswa/akademikMhs/khs', [MahasiswaController::class, 'khs'])->name('mahasiswa.akademikMhs.khs');
        Route::get('/mahasiswa/akademikMhs/transkrip', [MahasiswaController::class, 'transkrip'])->name('mahasiswa.akademikMhs.transkrip');
    });


    Route::prefix('akademik')->middleware('role:akademik')->name('akademik.')->group(function () {

        // Dashboard untuk akademik
        Route::get('/dashboard', [akademikControl::class, 'akademik'])->name('dashboard');

        // Pengaturan kelas
        Route::get('/aturkelas', [akademikControl::class, 'aturkelas'])->name('aturkelas');

        // Grup rute untuk Ruang Kelas
        Route::prefix('ruangkelas')->name('ruangkelas.')->group(function () {

            // Tampilkan daftar ruang kelas
            Route::get('/', [akademikControl::class, 'indexRuangKelas'])->name('index');

            // Tambahkan ruang kelas baru
            Route::post('/', [akademikControl::class, 'storeRuangKelas'])->name('store');

            // Edit ruang kelas (mengembalikan data untuk modal)
            Route::get('/{koderuang}/edit', [akademikControl::class, 'editRuangKelas'])
                ->where('koderuang', '[A-Za-z0-9_-]+') // Validasi format koderuang
                ->name('edit');

            // Update ruang kelas yang sudah ada
            Route::put('/{koderuang}', [akademikControl::class, 'updateRuangKelas'])
                ->where('koderuang', '[A-Za-z0-9_-]+') // Validasi format koderuang
                ->name('update');

            // Hapus ruang kelas
            Route::delete('/{koderuang}', [akademikControl::class, 'destroyRuangKelas'])
                ->where('koderuang', '[A-Za-z0-9_-]+') // Validasi format koderuang
                ->name('destroy');
        });
    });


    // Rute untuk dosen
    Route::group(['middleware' => 'role:dosen,kaprodi,dekan'], function () {
        Route::get('/dosen/dashboard', [DosenController::class, 'dosen'])->name('dosen.dashboard');
        Route::get('/dosen/verifikasi', [DosenController::class, 'verifikasi'])->name('dosen.verifikasi');
        Route::get('/dosen/lihatjadwal', [DosenController::class, 'lihatjadwal'])->name('dosen.lihatjadwal');
        Route::get('/dosen/konsultasi', [DosenController::class, 'konsultasi'])->name('dosen.konsultasi');
    });

    // Rute untuk kaprodi
    Route::group(['middleware' => 'role:kaprodi,dosen, dekan'], function () {
        Route::get('/kaprodi/dashboard', [KaprodiController::class, 'kaprodi'])->name('kaprodi.dashboard');
        // buat jadwal
        Route::get('/kaprodi/buatjadwal', [KaprodiController::class, 'buatjadwal'])->name('kaprodi.buatjadwal');
        Route::post('/kaprodi/jadwal/store', [KaprodiController::class, 'simpanJadwal'])->name('kaprodi.jadwal.store');
        Route::put('/kaprodi/jadwal/update/{id}', [KaprodiController::class, 'updateJadwal'])->name('kaprodi.jadwal.update');
        Route::get('/kaprodi/jadwal/delete/{id}', [KaprodiController::class, 'deleteJadwal'])->name('kaprodi.jadwal.delete');
        // daftar jadwal
        Route::get('/kaprodi/daftarJadwal', [KaprodiController::class, 'daftarJadwal'])->name('kaprodi.daftarJadwal');
    });


    // Rute untuk dekan
    Route::group(['middleware' => 'role:dekan,dosen, kaprodi'], function () {
        Route::get('/dekan/dashboard', [DekanController::class, 'dekan'])->name('dekan.dashboard');

        //Persetujuan Kelas
        Route::get('/dekan/persetujuan', [DekanController::class, 'persetujuanRuang'])->name('dekan.persetujuan');
        Route::get('/dekan/ruangkelas/approval', [DekanController::class, 'approveRuangKelas'])->name('dekan.ruangkelas.approval');
        Route::put('/dekan/ruangkelas/{koderuang}/approve', [DekanController::class, 'approveRoom'])->name('dekan.ruangkelas.approve');
        Route::put('/dekan/ruangkelas/{koderuang}/reject', [DekanController::class, 'rejectRoom'])->name('dekan.ruangkelas.reject');
        //Persetujuan Jadwal
        Route::get('/dekan/persetujuanJadwal', [DekanController::class, 'persetujuanJadwal'])->name('dekan.persetujuanJadwal');
        // Route::get('/dekan/jadwal/approval', [DekanController::class, 'jadwalApproval'])->name('dekan.jadwal.approval');
        // Rute untuk persetujuan jadwal oleh dekan
        Route::post('/dekan/jadwal/{id}/approve', [DekanController::class, 'approveJadwal'])->name('dekan.jadwal.approve');
        Route::post('/dekan/jadwal/{id}/reject', [DekanController::class, 'rejectJadwal'])->name('dekan.jadwal.reject');
    });

    // Rute untuk pemilihan menu oleh dekan dan kaprodi
    Route::group(['middleware' => 'role:dosen,dekan,kaprodi'], function () {
        Route::get('/pilihmenu', [PilihMenu::class, 'pilihmenu'])->name('pilihmenu');
        Route::get('/dosen/dashboard', [DosenController::class, 'dosen'])->name('dosen.dashboard');
    });

    // Logout
    Route::get('/logout', [AuthManager::class, 'logout'])->name('logout');
});
