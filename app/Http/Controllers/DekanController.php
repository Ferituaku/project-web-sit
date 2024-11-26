<?php

namespace App\Http\Controllers;

use App\Models\JadwalKuliah;
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
        return view('dekan.persetujuan', compact('ruangKelas'));
    }


    // New method to show room approval page
    public function ruangKelasApproval()
    {
        // Fetch all rooms with their current approval status
        $ruangKelas = RuangKelas::with('jadwalKuliah')->paginate(10);
        return view('dekan.ruangkelas.approval', compact('ruangKelas'));
    }

    // Method to approve a room
    public function approveRoom($koderuang)
    {
        try {
            $ruangKelas = RuangKelas::where('koderuang', $koderuang)->firstOrFail();

            // Gunakan kolom 'approval' sesuai dengan migration
            $ruangKelas->approval = true;
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
            $ruangKelas->approval = false;
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
            $jadwal->rejection_reason = null;
            $jadwal->save();

            return response()->json([
                'status' => 'sukses',
                'pessan' => 'Jadwal Berhasil disetujui',
                'reload' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'pesan' => 'Gagal menyetujui jadwal: ' . $e->getMessage()
            ], 500);
        }
    }
    public function rejectJadwal(Request $request, $id)
    {
        try {
            $request->validate([
                'rejection_reason' => 'required|string|max:255'
            ]);

            $jadwal = JadwalKuliah::findOrFail($id);
            $jadwal->approval = '2';
            $jadwal->rejection_reason = $request->rejection_reason;
            $jadwal->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Jadwal kuliah berhasil ditolak',
                'reload' => true
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menolak jadwal: ' . $e->getMessage()
            ], 500);
        }
    }
}
