<?php

namespace App\Http\Controllers;

use App\Models\RuangKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class akademikControl extends Controller
{
    public function akademik()
    {
        return view('akademik/dashboard');
    }

    public function aturkelas()
    {
        // Get all ruang kelas for display in the view
        $ruangKelas = RuangKelas::orderBy('koderuang')->paginate(10);
        return view('akademik/aturkelas', compact('ruangKelas'));
    }

    // CRUD methods for RuangKelas
    public function storeRuangKelas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'koderuang' => 'required|unique:ruangKelas,koderuang',
            'kapasitas' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        RuangKelas::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Ruang kelas berhasil ditambahkan'
        ]);
    }

    public function updateRuangKelas(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'koderuang' => 'required|unique:ruangKelas,koderuang,' . $id,
            'kapasitas' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $ruangKelas = RuangKelas::findOrFail($id);
        $ruangKelas->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Ruang kelas berhasil diperbarui'
        ]);
    }

    public function getRuangKelas($id)
    {
        $ruangKelas = RuangKelas::findOrFail($id);
        return response()->json($ruangKelas);
    }

    public function destroyRuangKelas($id)
    {
        $ruangKelas = RuangKelas::findOrFail($id);
        $ruangKelas->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Ruang kelas berhasil dihapus'
        ]);
    }
}
