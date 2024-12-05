<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Irs;
use App\Models\Mahasiswa;

class DosenController extends Controller
{
    public function dosen()
    {
        return view('dosen/dashboard');
    }

    public function verifikasi()
    {
        $irs = Irs::with(['mahasiswa:nim,name,semester'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dosen.verifikasi', compact('irs'));
    }

    public function lihatjadwal()
    {
        return view('dosen/lihatjadwal');
    }

    public function konsultasi()
    {
        return view('dosen/konsultasi');
    }

    // New methods for IRS approval
    public function showIrsDetail($id)
    {
        try {
            $irs = Irs::with('mahasiswa')->findOrFail($id);
            return response()->json([
                'status' => 'success',
                'irs' => $irs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memuat detail IRS'
            ], 500);
        }
    }

    public function approveIrs($id)
    {
        try {
            $irs = Irs::findOrFail($id);
            $irs->approval = 1; // 1 for approved
            $irs->save();

            return response()->json([
                'status' => 'success',
                'message' => 'IRS berhasil disetujui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyetujui IRS'
            ], 500);
        }
    }

    public function rejectIrs($id)
    {
        try {
            $irs = Irs::findOrFail($id);
            $irs->approval = 2; // 2 for rejected
            $irs->save();

            return response()->json([
                'status' => 'success',
                'message' => 'IRS berhasil ditolak'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menolak IRS'
            ], 500);
        }
    }
}