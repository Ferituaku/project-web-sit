<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Irs;
use App\Models\Mahasiswa;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

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

        $mahasiswa = Mahasiswa::where('dosen_id', $dosenID)
            ->with(['irs' => function($query) {
                $query->latest();
            }])
            ->paginate(10);

        return view('dosen.irs', compact('mahasiswa'));
    }

    private function checkModificationPeriod($irs)
    {
        $createdDate = Carbon::parse($irs->created_at);
        $now = Carbon::now();
        $daysSinceCreation = $createdDate->diffInDays($now);

        return $daysSinceCreation <= 14; // 14 hari = 2 minggu
    }
    private function checkCancellationPeriod($irs)
    {
        try {
            // Pastikan IRS sudah disetujui
            if ($irs->approval !== '1') {
                return false;
            }

            $approvalDate = Carbon::parse($irs->updated_at);
            $now = Carbon::now();
            $daysSinceApproval = $approvalDate->diffInDays($now);

            return $daysSinceApproval <= 28; // 28 hari = 4 minggu
        } catch (\Exception $e) {
            return false;
        }
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
                    'approval' => $irs->approval,
                    'updated_at' => $irs->updated_at,
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
    public function enableIrsEdit($id)
    {
        try {
            DB::beginTransaction();

            $irs = Irs::findOrFail($id);

            // Verify if IRS is approved
            if ($irs->approval !== '1') {
                throw new \Exception('IRS tidak dalam status disetujui');
            }

            // Check if within 2 weeks period using existing method
            if (!$this->checkModificationPeriod($irs)) {
                throw new \Exception('Periode edit IRS telah berakhir (2 minggu setelah persetujuan)');
            }

            // Update IRS status to pending
            $irs->approval = '0';
            $irs->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'IRS berhasil dibuka untuk diedit'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuka edit IRS: ' . $e->getMessage()
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


    public function cancelApprovedIrs($id)
    {
        try {
            DB::beginTransaction();

            $irs = Irs::findOrFail($id);


            // Cek apakah IRS sudah disetujui
            if ($irs->approval !== '1') {
                throw new \Exception('IRS belum disetujui atau sudah dibatalkan');
            }

            if ($this->checkCancellationPeriod($irs->updated_at)) {
                throw new \Exception('Periode pembatalan IRS telah berakhir');
            }
            // Update status IRS menjadi dibatalkan
            $irs->approval = '0';

            $irs->save();

            DB::commit();

            return response()->json([
                'status' => 'success',

                'message' => 'IRS berhasil dibatalkan'

            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',

                'message' => 'Gagal membatalkan IRS: ' . $e->getMessage()
            ], 500);
        }
    }

    public function printIrsMhs($id)
    {
        try {
            // Langsung ambil data IRS berdasarkan ID beserta relasinya
            $irs = Irs::findOrFail($id);

            $data = [
                'irs' => $irs,
                'mahasiswa' => $irs->mahasiswa,
                'semester_text' => $irs->semester % 2 == 1 ? 'Ganjil' : 'Genap'
            ];

            $pdf = PDF::loadView('mahasiswa.akademikMhs.cetak-irs', $data);
            $pdf->setPaper('A4', 'portrait');
            $filename = sprintf(
                'IRS-%s-%s-SMT%d.pdf',
                $irs->mahasiswa->nim,
                str_replace('/', '-', $irs->tahun_ajaran),
                $irs->semester
            );

            return $pdf->stream($filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mencetak IRS: ' . $e->getMessage());
        }
    }
}
