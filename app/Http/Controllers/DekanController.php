<?php

namespace App\Http\Controllers;

use App\Models\JadwalKuliah;
use App\Models\ProgramStudi;
use App\Models\RuangKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class DekanController extends Controller
{
    public function dekan()
    {
        return view('dekan.dashboard');
    }

    public function pilihmenu()
    {
        return view('pilihmenu');
    }

    public function dosen()
    {
        return view('dosen.dashboard');
    }
    public function persetujuanRuang()
    {
        $ruangKelas = RuangKelas::with('jadwalKuliah')->paginate(10);
        $programStudi = ProgramStudi::all();

        return view('dekan.persetujuan', compact('ruangKelas', 'programStudi'));
    }


    // New method to show room approval page
    public function ruangKelasApproval()
    {
        // Fetch all rooms with their current approval status
        $ruangKelas = RuangKelas::with('jadwalKuliah')->paginate(10);
        return view('dekan.ruangkelas.approval', compact('ruangKelas'));
    }

    // Method to approve a room
    public function approveRoom(Request $request, $koderuang)
    {
        try {
            $ruangKelas = RuangKelas::where('koderuang', $koderuang)->firstOrFail();

            // Gunakan kolom 'approval' sesuai dengan migration
            $ruangKelas->approval = '1';
            $ruangKelas->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Ruang kelas berhasil disetujui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyetujui ruang kelas: ' . $e->getMessage()
            ], 500);
        }
    }


    public function rejectRoom($koderuang)
    {
        try {
            $ruangKelas = RuangKelas::where('koderuang', $koderuang)->firstOrFail();

            // Gunakan kolom 'approval' sesuai dengan migration
            $ruangKelas->approval = '2';
            $ruangKelas->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Ruang kelas ditolak'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menolak ruang kelas: ' . $e->getMessage()
            ], 500);
        }
    }


    public function persetujuanJadwal()
    {
        $jadwalKuliah = JadwalKuliah::with(['ruangKelas', 'matakuliah', 'pembimbingakd'])->paginate(10);
        return view('dekan.persetujuanJadwal', compact('jadwalKuliah'));
    }

    public function approveJadwal($id)
    {
        try {
            $jadwal = JadwalKuliah::with(['ruangKelas', 'matakuliah', 'pembimbingakd'])->where('id', $id)->findOrFail(($id));
            $jadwal->approval = '1';
            // $jadwal->rejection_reason = null;
            $jadwal->save();

            return response()->json([
                'status' => 'sukses',
                'message' => 'Jadwal Berhasil disetujui',
                'reload' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyetujui jadwal: ' . $e->getMessage()
            ], 500);
        }
    }
    public function rejectJadwal($id)
    {
        try {
            $jadwal = JadwalKuliah::findOrFail($id);
            $jadwal->approval = '2';
            $jadwal->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Jadwal kuliah berhasil ditolak',
                'reload' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menolak jadwal: ' . $e->getMessage()
            ], 500);
        }
    }
}
