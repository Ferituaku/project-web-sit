<?php

namespace App\Http\Controllers;

use App\Models\RuangKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function persetujuan()
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
}
