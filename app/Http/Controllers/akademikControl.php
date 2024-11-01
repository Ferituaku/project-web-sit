<?php

namespace App\Http\Controllers;

use App\Models\RuangKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class akademikControl extends Controller
{

    public function akademik()
    {
        $ruangKelas = RuangKelas::count();


        return view('akademik.dashboard', compact(
            'ruangKelas',
        ));
    }

    public function aturkelas()
    {
        $ruangKelas = RuangKelas::with('jadwalKuliah')->paginate(10);
        return view('akademik.aturkelas', compact('ruangKelas'));
    }

    public function indexRuangKelas()
    {
        $ruangKelas = RuangKelas::with('jadwalKuliah')->paginate(10);
        return view('akademik.ruangkelas.index', compact('ruangKelas'));
    }

    public function storeRuangKelas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'koderuang' => 'required|unique:ruangkelas,koderuang',
            'kapasitas' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            RuangKelas::create($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Ruang kelas berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan ruang kelas'
            ], 500);
        }
    }

    public function editRuangKelas($koderuang)
    {
        try {
            $ruangKelas = RuangKelas::where('koderuang', $koderuang)->firstOrFail();
            return response()->json($ruangKelas);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ruang kelas tidak ditemukan'
            ], 404);
        }
    }

    public function updateRuangKelas(Request $request, $koderuang)
    {
        $validator = Validator::make($request->all(), [
            'new_koderuang' => 'required|unique:ruangkelas,koderuang,' . $koderuang . ',koderuang',
            'kapasitas' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $ruangKelas = RuangKelas::where('koderuang', $koderuang)->firstOrFail();
            $ruangKelas->update([
                'koderuang' => $request->new_koderuang,
                'kapasitas' => $request->kapasitas
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Ruang kelas berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui ruang kelas'
            ], 500);
        }
    }

    public function destroyRuangKelas($koderuang)
    {
        DB::beginTransaction();
        try {
            $ruangKelas = RuangKelas::where('koderuang', $koderuang)->first();

            if (!$ruangKelas) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ruang kelas tidak ditemukan'
                ], 404);
            }

            // Check if room has associated schedules
            if ($ruangKelas->jadwalKuliah()->count() > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak dapat menghapus ruang kelas karena masih memiliki jadwal kuliah'
                ], 422);
            }

            $ruangKelas->delete();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Ruang kelas berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
