<?php

namespace App\Http\Controllers;

use App\Models\Irs;
use App\Models\JadwalKuliah;
use App\Models\Matakuliah;
use App\Models\PembimbingAkd;
use App\Models\RuangKelas;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MahasiswaController extends Controller
{
    public function mahasiswa()
    {
        return view('mahasiswa/dashboard');
    }
    public function jadwal()
    {
        return view('mahasiswa/jadwal');
    }
    // public function akademisi()
    // {
    //     return view('mahasiswa/akademisi');
    // }
    public function herreg()
    {
        return view('mahasiswa/herreg');
    }
    public function biaya()
    {
        return view('mahasiswa/biaya');
    }

    public function irs()
    {
        $mahasiswa = Auth::user();
        $jadwal = Irs::all();
        //     $irs = $mahasiswa->irs;

        //     if ($irs->count() > 0) {
        //         $jadwal = $irs->map(function ($item) {
        //             return $item->jadwalKuliah;
        //         })->flatten();
        //     } else {
        //         $jadwal = [];
        //     }

        return view('mahasiswa.irs', compact('jadwal',));
    }


    public function akademisi(Request $request)
    {
        try {
            $semester = $request->input('semester');
            $mahasiswa = Auth::user();

            // Get current IRS if exists
            $currentIrs = Irs::where('nim', $mahasiswa->nim)->first();

            // Get all selected jadwal IDs
            $selectedJadwalIds = [];
            if ($currentIrs) {
                for ($i = 1; $i <= 8; $i++) {
                    $field = "jadwal_id_$i";
                    if ($currentIrs->$field) {
                        $selectedJadwalIds[] = $currentIrs->$field;
                    }
                }
            }

            // Query jadwal kuliah
            $query = JadwalKuliah::with(['mataKuliah', 'ruangKelas', 'pembimbingakd'])
                ->orderBy('jam_mulai');

            if ($semester) {
                $query->where('plot_semester', $semester);
            }

            $jadwalKuliah = $query->get();

            // Create time matrix
            $timeSlots = $this->createTimeSlots();
            $scheduleMatrix = $this->createScheduleMatrix($timeSlots, $jadwalKuliah);

            return view('mahasiswa.akademisi', compact(
                'scheduleMatrix',
                'jadwalKuliah',
                'timeSlots',
                'selectedJadwalIds',
                'currentIrs',
                'semester'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function createTimeSlots()
    {
        $timeSlots = [];
        $startTime = strtotime('07:00');
        $endTime = strtotime('17:00');

        while ($startTime <= $endTime) {
            $timeSlots[] = date('H:i', $startTime);
            $startTime = strtotime('+1 hour', $startTime);
        }

        return $timeSlots;
    }

    private function createScheduleMatrix($timeSlots, $jadwalKuliah)
    {
        $scheduleMatrix = [];
        foreach ($timeSlots as $time) {
            $scheduleMatrix[$time] = [
                'Senin' => [],
                'Selasa' => [],
                'Rabu' => [],
                'Kamis' => [],
                'Jumat' => []
            ];
        }

        foreach ($jadwalKuliah as $jadwal) {
            $timeSlot = date('H:i', strtotime($jadwal->jam_mulai));
            if (isset($scheduleMatrix[$timeSlot][$jadwal->hari])) {
                $scheduleMatrix[$timeSlot][$jadwal->hari][] = $jadwal;
            }
        }

        return $scheduleMatrix;
    }

    public function saveIrs(Request $request)
    {
        try {
            DB::beginTransaction();

            $mahasiswa = Auth::user();
            $selectedJadwals = $request->input('jadwals', []);

            // Validate total SKS
            $totalSks = JadwalKuliah::whereIn('id', $selectedJadwals)
                ->join('matakuliah', 'jadwalKuliah.kodemk', '=', 'matakuliah.kodemk')
                ->sum('matakuliah.sks');

            if ($totalSks > 24) {
                return response()->json([
                    'success' => false,
                    'message' => 'Total SKS melebihi batas maksimum (24 SKS)'
                ], 400);
            }

            // Check for schedule conflicts
            if ($this->hasScheduleConflict($selectedJadwals)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terdapat jadwal yang bertabrakan'
                ], 400);
            }

            // Get or create IRS record
            $irs = Irs::firstOrNew(['nim' => $mahasiswa->nim]);

            // Reset all jadwal_id fields
            for ($i = 1; $i <= 8; $i++) {
                $irs->{"jadwal_id_$i"} = null;
            }

            // Assign selected jadwals to fields
            foreach ($selectedJadwals as $index => $jadwalId) {
                $fieldNum = $index + 1;
                if ($fieldNum <= 8) {
                    $irs->{"jadwal_id_$fieldNum"} = $jadwalId;
                }
            }

            $irs->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'IRS berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    private function hasScheduleConflict($jadwalIds)
    {
        $jadwals = JadwalKuliah::whereIn('id', $jadwalIds)->get();

        for ($i = 0; $i < count($jadwals); $i++) {
            for ($j = $i + 1; $j < count($jadwals); $j++) {
                if ($jadwals[$i]->hari === $jadwals[$j]->hari) {
                    $start1 = strtotime($jadwals[$i]->jam_mulai);
                    $end1 = strtotime($jadwals[$i]->jam_selesai);
                    $start2 = strtotime($jadwals[$j]->jam_mulai);
                    $end2 = strtotime($jadwals[$j]->jam_selesai);

                    if (($start1 < $end2) && ($start2 < $end1)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}
