<?php

namespace App\Http\Controllers;

use App\Models\ProgramStudi;
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
        $programStudi = ProgramStudi::all();
        $ruangKelas = RuangKelas::with('jadwalKuliah', 'programStudi')->orderBy('created_at', 'desc')->paginate(10);
        return view('akademik.aturkelas', compact('ruangKelas', 'programStudi'));
    }

    // public function indexRuangKelas()
    // {
    //     $programStudi = ProgramStudi::all();
    //     $ruangKelas = RuangKelas::with('jadwalKuliah', 'programStudi');
    //     return view('akademik.ruangkelas.index', compact('ruangKelas', 'programStudi'));
    // }

    public function storeRuangKelas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'koderuang' => 'required|unique:ruangkelas,koderuang',
            'kapasitas' => 'required|integer|min:1',
            'program_studi_id' => 'required|exists:program_studi,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            RuangKelas::create([
                'koderuang' => $request->koderuang,
                'kapasitas' => $request->kapasitas,
                'program_studi_id' => $request->program_studi_id,
                'approval' => '0',
            ]);
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
            $ruangKelas = RuangKelas::with('programStudi')->where('koderuang', $koderuang)->firstOrFail();
            $programStudi = ProgramStudi::all(); // Mendapatkan semua program studi
            return response()->json([
                'status' => 'success',
                'ruangKelas' => $ruangKelas,
                'programStudi' => $programStudi,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ruang kelas tidak ditemukan',
            ], 404);
        }
    }

    public function updateRuangKelas(Request $request, $koderuang)
    {
        $validator = Validator::make($request->all(), [
            'new_koderuang' => 'required|unique:ruangkelas,koderuang,' . $koderuang . ',koderuang',
            'kapasitas' => 'required|integer|min:1',
            'program_studi_id' => 'required|exists:program_studi,id',
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
                'kapasitas' => $request->kapasitas,
                'program_studi_id' => $request->program_studi_id,
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
