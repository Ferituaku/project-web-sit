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

    public function approveRuangKelas()
    {
        $ruangKelas = RuangKelas::with('jadwalKuliah')->where('approved', false)->paginate(10);
        return view('dekanat.ruangkelas.index', compact('ruangKelas'));
    }

    public function approveRoom(Request $request, $koderuang)
    {
        try {
            $ruangKelas = RuangKelas::where('koderuang', $koderuang)->firstOrFail();
            $ruangKelas->jadwalKuliah()->update(['approved' => true]);
            return response()->json([
                'status' => 'success',
                'message' => 'Ruang kelas berhasil disetujui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyetujui ruang kelas'
            ], 500);
        }
    }

    public function rejectRoom(Request $request, $koderuang)
    {
        try {
            $ruangKelas = RuangKelas::where('koderuang', $koderuang)->firstOrFail();
            $ruangKelas->jadwalKuliah()->update(['approved' => false]);
            return response()->json([
                'status' => 'success',
                'message' => 'Ruang kelas berhasil ditolak'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menolak ruang kelas'
            ], 500);
        }
    }
}
