<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Irs;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DosenController extends Controller
{
    public function dosen()
    {
        return view('dosen/dashboard');
    }


    public function lihatjadwal()
    {
        return view('dosen/lihatjadwal');
    }

    public function konsultasi()
    {
        return view('dosen/konsultasi');
    }
    public function irs()
    {
        $dosenID = Auth::user()->nip;

        $irs = Irs::with(['mahasiswa:nim,name'])
            ->whereHas('mahasiswa', function($query) use ($dosenID) {
                $query->where('dosen_id', $dosenID);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dosen.irs', compact('irs'));
    }

    public function showIrsDetail($id)
    {
        try {
            $irs = Irs::with([
                'mahasiswa:nim,name',
                'jadwalKuliah' => function ($query) {
                    $query->select(
                        'jadwalKuliah.id',
                        'jadwalKuliah.kodemk',
                        'jadwalKuliah.hari',
                        'jadwalKuliah.jam_mulai',
                        'jadwalKuliah.jam_selesai',
                        'jadwalKuliah.ruangkelas_id',
                        'jadwalKuliah.dosen_id',
                    )->with([
                        'mataKuliah:kodemk,nama_mk,sks',
                        'ruangKelas:koderuang',
                        'pembimbingakd:name,nip'
                    ]);
                }
            ])->findOrFail($id);

            $courseData = $irs->jadwalKuliah->map(function ($jadwal) {
                return [
                    'id' => $jadwal->id,
                    'kodemk' => $jadwal->kodemk,
                    'nama_mk' => $jadwal->mataKuliah->nama_mk,
                    'sks' => $jadwal->mataKuliah->sks,
                    'kelas' => $jadwal->ruangKelas ? $jadwal->ruangKelas->koderuang : '-',
                    'schedule' => $jadwal->hari . ' ' . $jadwal->jam_mulai . '-' . $jadwal->jam_selesai,
                    'dosen' => $jadwal->pembimbingakd ? $jadwal->pembimbingakd->name : '-',
                ];
            });

            return response()->json([
                'status' => 'success',
                'irs' => [
                    'id' => $irs->id,
                    'total_sks' => $irs->total_sks,
                    'semester' => $irs->semester,
                    'tahun_ajaran' => $irs->tahun_ajaran,
                    'courses' => $courseData
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memuat detail IRS: ' . $e->getMessage()
            ], 500);
        }
    }
    public function approveIrs($id)
    {
        try {
            DB::beginTransaction();

            $irs = Irs::findOrFail($id);

            // Verify if IRS is still in pending state
            if ($irs->approval != '0') {
                throw new \Exception('IRS sudah diproses sebelumnya');
            }

            $irs->approval = '1';
            $irs->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'IRS berhasil disetujui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyetujui IRS: ' . $e->getMessage()
            ], 500);
        }
    }

    public function rejectIrs($id)
    {
        try {
            DB::beginTransaction();

            $irs = Irs::findOrFail($id);

            // Verify if IRS is still in pending state
            if ($irs->approval != '0') {
                throw new \Exception('IRS sudah diproses sebelumnya');
            }

            $irs->approval = '2';
            $irs->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'IRS berhasil ditolak'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menolak IRS: ' . $e->getMessage()
            ], 500);
        }
    }
}
