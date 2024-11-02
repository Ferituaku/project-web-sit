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
        Route::get('/mahasiswa/akademisi', [MahasiswaController::class, 'akademisi'])->name('mahasiswa.akademisi');
        Route::get('/mahasiswa/kulon', [MahasiswaController::class, 'kulon'])->name('mahasiswa.kulon');
    });

    // Rute untuk akademik
    Route::group(['middleware' => 'role:akademik'], function () {
        Route::get('/akademik/dashboard', [akademikControl::class, 'akademik'])->name('akademik.dashboard');
        Route::get('/akademik/aturkelas', [akademikControl::class, 'aturkelas'])->name('akademik.aturkelas');

        // New routes for RuangKelas CRUD operations
        Route::post('/akademik/ruangkelas', [akademikControl::class, 'storeRuangKelas'])->name('akademik.ruangkelas.store');
        Route::get('/akademik/ruangkelas/{koderuang}', [akademikControl::class, 'getRuangKelas'])->name('akademik.ruangkelas.get');
        Route::get('/akademik/ruangkelas/{koderuang}/edit', [akademikControl::class, 'editRuangKelas'])->name('akademik.ruangkelas.edit');
        Route::put('/akademik/ruangkelas/{koderuang}', [akademikControl::class, 'updateRuangKelas'])->name('akademik.ruangkelas.update');
        Route::delete('/akademik/ruangkelas/{koderuang}', [akademikControl::class, 'destroyRuangKelas'])->name('akademik.ruangkelas.destroy');
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
        Route::get('/kaprodi/buatjadwal', [KaprodiController::class, 'buatjadwal'])->name('kaprodi.buatjadwal');
        Route::get('/kaprodi/jadwal', [KaprodiController::class, 'buatjadwal'])->name('kaprodi.jadwal');
        Route::post('/kaprodi/jadwal/store', [KaprodiController::class, 'simpanJadwal'])->name('kaprodi.jadwal.store');
        Route::put('/kaprodi/jadwal/update/{id}', [KaprodiController::class, 'updateJadwal'])->name('kaprodi.jadwal.update');
        Route::get('/kaprodi/jadwal/delete/{id}', [KaprodiController::class, 'deleteJadwal'])->name('kaprodi.jadwal.delete');
    });


    // Rute untuk dekan
    Route::group(['middleware' => 'role:dekan,dosen, kaprodi'], function () {
        Route::get('/dekan/dashboard', [DekanController::class, 'dekan'])->name('dekan.dashboard');
        Route::get('/dekan/persetujuan', [DekanController::class, 'persetujuan'])->name('dekan.persetujuan');
        //Persetujuan Kelas
        Route::get('/dekan/ruangkelas/approval', [DekanController::class, 'approveRuangKelas'])->name('dekan.ruangkelas.approval');
        Route::put('/dekan/ruangkelas/{koderuang}/approve', [DekanController::class, 'approveRoom'])->name('dekan.ruangkelas.approve');
        Route::put('/dekan/ruangkelas/{koderuang}/reject', [DekanController::class, 'rejectRoom'])->name('dekan.ruangkelas.reject');
        //Perserujuan Jadwal

    });

    // Rute untuk pemilihan menu oleh dekan dan kaprodi
    Route::group(['middleware' => 'role:dosen,dekan,kaprodi'], function () {
        Route::get('/pilihmenu', [PilihMenu::class, 'pilihmenu'])->name('pilihmenu');
        Route::get('/dosen/dashboard', [DosenController::class, 'dosen'])->name('dosen.dashboard');
    });

    // Logout
    Route::get('/logout', [AuthManager::class, 'logout'])->name('logout');
});
