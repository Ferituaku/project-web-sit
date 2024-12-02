<?php

namespace App\Http\Controllers;

use App\Models\Irs;
use App\Models\JadwalKuliah;
use App\Models\Matakuliah;
use App\Models\PembimbingAkd;
use App\Models\RuangKelas;
use Barryvdh\DomPDF\Facade\Pdf;
use Dotenv\Exception\ValidationException;
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
    public function akademik()
    {
        return view('mahasiswa.akademikMhs.akademik-base');
    }
    public function hasilirs()
    {
        
        return view('mahasiswa.akademikMhs.hasilirs');
    }
    public function khs()
    {
        return view('mahasiswa.akademikMhs.khs');
    }

    public function transkrip()
    {
        return view('mahasiswa.akademikMhs.transkrip');
    }

    public function buatIrs(Request $request)
    {
        try {
            $mahasiswa = Auth::user();
            $semester = $request->input('semester');

            // Get current IRS if exists
            $currentIrs = Irs::where('nim', $mahasiswa->nim)->first();

            // Get all selected jadwal IDs

            // Perubahan buat pada table IRS 1 nim 1 jadwal_id, dan data pada kolom nim dan jadwal_id yang berada pada satu kolom bisa sama, jadi memungkinkan untuk 1 nim mengambil banyak jadwal matkul (jadwal_id lebih dari 1)
            $selectedJadwalIds = [];
            if ($currentIrs) {
                $selectedJadwalIds = $currentIrs->jadwalKuliah->pluck('id')->toArray();
            }

            $query = JadwalKuliah::with(['mataKuliah', 'ruangKelas', 'pembimbingakd'])
                ->orderBy('jam_mulai');

            if ($semester) {
                $query->where('plot_semester', $semester);
            }


            $jadwalKuliah = $query->get();

            // Create time matrix
            $timeSlots = $this->createTimeSlots();
            $scheduleMatrix = $this->createScheduleMatrix($timeSlots, $jadwalKuliah);

            if ($request->expectsJson()) {
                return response()->json($jadwalKuliah);
            }

            return view('mahasiswa.akademikMhs.buatIrs', compact(
                'scheduleMatrix',
                'jadwalKuliah',
                'timeSlots',
                'selectedJadwalIds',
                'currentIrs',
                'semester'
            ));
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function createTimeSlots()
    {
        $timeSlots = [];
        $startTime = strtotime('07:00');
        $endTime = strtotime('21:00');

        while ($startTime <= $endTime) {
            $timeSlots[] = date('H:i', $startTime);
            $startTime = strtotime('+1 hour', $startTime);
        }

        return $timeSlots;
    }

    private function createScheduleMatrix($timeSlots, $jadwalKuliah)
    {
        $scheduleMatrix = [];
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        // Get all jadwal that are approved
        $jadwalKuliah = JadwalKuliah::with(['matakuliah', 'ruangKelas'])
            ->orderBy('jam_mulai')
            ->get();

        // Initialize the matrix with empty arrays
        foreach ($timeSlots as $time) {
            $scheduleMatrix[$time] = array_fill_keys($days, []);
        }

        // Fill the matrix with jadwal
        foreach ($jadwalKuliah as $jadwal) {
            $timeSlot = date('H:i', strtotime($jadwal->jam_mulai));
            if (isset($scheduleMatrix[$timeSlot][$jadwal->hari])) {
                $scheduleMatrix[$timeSlot][$jadwal->hari][] = $jadwal;
            }
        }

        return $scheduleMatrix;
    }

    // public function saveIrs(Request $request)
    // {
    //     try {
    //         DB::beginTransaction();

    //         $mahasiswa = Auth::user();
    //         $selectedJadwals = $request->input('jadwals', []);
    //         $semester = $request->input('semester');
    //         $tahunAjaran = $request->input('tahun_ajaran');

    //         if (empty($semester) || empty($tahunAjaran)) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Semester dan tahun ajaran harus diisi'
    //             ], 400);
    //         }

    //         // Validate total SKS
    //         $totalSks = JadwalKuliah::whereIn('id', $selectedJadwals)
    //             ->join('matakuliah', 'jadwalKuliah.kodemk', '=', 'matakuliah.kodemk')
    //             ->sum('matakuliah.sks');

    //         if ($totalSks > 24) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Total SKS melebihi batas maksimum (24 SKS)'
    //             ], 400);
    //         }

    //         // Check for schedule conflicts
    //         if ($this->hasScheduleConflict($selectedJadwals)) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Terdapat jadwal yang bertabrakan'
    //             ], 400);
    //         }

    //         $irs = Irs::updateOrCreate(
    //             [
    //                 'nim' => $mahasiswa->nim,
    //                 'semester' => $semester,
    //                 'tahun_ajaran' => $tahunAjaran
    //             ],
    //             [
    //                 'total_sks' => $totalSks,
    //                 'approval' => '0' // Reset approval status on update
    //             ]
    //         );
    //         // Update total SKS
    //         $irs->total_sks = $totalSks;


    //         // Sync jadwal kuliah
    //         $irs->jadwalKuliah()->sync($selectedJadwals);

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'IRS berhasil disimpan'
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }


    // public function saveIrs(Request $request)
    // {
    //     try {
    //         DB::beginTransaction();

    //         $mahasiswa = Auth::user();
    //         $selectedJadwals = $request->input('jadwals', []);

    //         // $semester = $mahasiswa->semester;
    //         // $tahunAjaran = $mahasiswa->tahun_ajaran;

    //         // Validate input
    //         $this->validateIrsInput($selectedJadwals);

    //         // Calculate total SKS
    //         $totalSks = $this->calculateTotalSks($selectedJadwals);

    //         // Validate SKS limit
    //         if ($totalSks > 24) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Total SKS melebihi batas maksimum (24 SKS)'
    //             ], 400);
    //         }

    //         // Check for schedule conflicts
    //         if ($this->hasScheduleConflict($selectedJadwals)) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Terdapat jadwal yang bertabrakan'
    //             ], 400);
    //         }
    //         // Create new IRS record
    //         $irs = new Irs();
    //         $irs->nim = $mahasiswa->nim;
    //         $irs->semester = $mahasiswa->semester;
    //         $irs->tahun_ajaran = $mahasiswa->tahun_ajaran;
    //         $irs->total_sks = $totalSks;
    //         $irs->approval = '0'; // Default to pending approval
    //         $irs->save();

    //         $pivotData = array_map(function ($jadwalId) {
    //             return [
    //                 'jadwal_id' => $jadwalId,
    //                 'created_at' => now(),
    //                 'updated_at' => now()
    //             ];
    //         }, $selectedJadwals);

    //         // Insert into irs_jadwal pivot table
    //         foreach ($pivotData as $data) {
    //             DB::table('irs_jadwal')->insert([
    //                 'irs_id' => $irs->id,
    //                 'jadwal_id' => $data['jadwal_id'],
    //                 'created_at' => $data['created_at'],
    //                 'updated_at' => $data['updated_at']
    //             ]);
    //         }

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'IRS berhasil disimpan dan menunggu persetujuan dosen wali'
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Error saving IRS: ' . $e->getMessage());
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Terjadi kesalahan saat menyimpan IRS: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function saveIrs(Request $request)
    {
        try {
            DB::beginTransaction();

            $mahasiswa = Auth::user();
            $selectedJadwals = $request->input('jadwals', []);

            // Validasi input
            if (empty($selectedJadwals)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pilih minimal satu mata kuliah'
                ], 400);
            }

            // Ambil semester dari jadwal kuliah yang dipilih pertama
            $jadwalKuliah = JadwalKuliah::find($selectedJadwals[0]);
            $semester = $jadwalKuliah->plot_semester; // Mengambil semester dari jadwal kuliah

            // Hitung total SKS
            $totalSks = JadwalKuliah::whereIn('id', $selectedJadwals)
                ->join('matakuliah', 'jadwalKuliah.kodemk', '=', 'matakuliah.kodemk')
                ->sum('matakuliah.sks');

            // Validasi batas SKS
            if ($totalSks > 24) {
                return response()->json([
                    'success' => false,
                    'message' => 'Total SKS melebihi batas maksimum (24 SKS)'
                ], 400);
            }

            // Cek konflik jadwal
            if ($this->hasScheduleConflict($selectedJadwals)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terdapat jadwal yang bertabrakan'
                ], 400);
            }

            // Set tahun ajaran
            $currentYear = date('Y');
            $month = date('n');
            if ($month >= 8) { // Semester Ganjil
                $tahunAjaran = $currentYear . '/' . ($currentYear + 1);
            } else { // Semester Genap
                $tahunAjaran = ($currentYear - 1) . '/' . $currentYear;
            }

            // Cari IRS yang sudah ada untuk semester dan tahun ajaran ini
            $existingIrs = Irs::where('nim', $mahasiswa->nim)
                ->where('semester', $semester)
                ->where('tahun_ajaran', $tahunAjaran)
                ->first();

            if ($existingIrs) {
                // Update IRS yang sudah ada
                $existingIrs->total_sks = $totalSks;
                $existingIrs->approval = '0'; // Reset status approval
                $existingIrs->save();

                // Hapus semua jadwal lama
                DB::table('irs_jadwal')->where('irs_id', $existingIrs->id)->delete();

                // Masukkan jadwal baru
                foreach ($selectedJadwals as $jadwalId) {
                    DB::table('irs_jadwal')->insert([
                        'irs_id' => $existingIrs->id,
                        'jadwal_id' => $jadwalId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            } else { // Buat record IRS baru
                $irs = new Irs();
                $irs->nim = $mahasiswa->nim;
                $irs->semester = $semester; // Menggunakan semester dari jadwal
                $irs->tahun_ajaran = $tahunAjaran;
                $irs->total_sks = $totalSks;
                $irs->approval = '0';
                $irs->save();

                // Attach jadwal yang dipilih ke IRS melalui tabel pivot
                foreach ($selectedJadwals as $jadwalId) {
                    DB::table('irs_jadwal')->insert([
                        'irs_id' => $irs->id,
                        'jadwal_id' => $jadwalId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }


            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'IRS berhasil disimpan dan menunggu persetujuan dosen wali'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving IRS: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan IRS: ' . $e->getMessage()
            ], 500);
        }
    }

    private function validateIrsInput($selectedJadwals)
    {
        if (empty($selectedJadwals)) {
            throw new ValidationException('Pilih minimal satu mata kuliah');
        }
    }

    private function calculateTotalSks($selectedJadwals)
    {
        return JadwalKuliah::whereIn('id', $selectedJadwals)
            ->join('matakuliah', 'jadwalKuliah.kodemk', '=', 'matakuliah.kodemk')
            ->sum('matakuliah.sks');
    }

    private function createOrUpdateIrs($mahasiswa, $semester, $tahunAjaran, $totalSks)
    {
        return Irs::updateOrCreate(
            [
                'nim' => $mahasiswa->nim,
                'semester' => $semester,
                'tahun_ajaran' => $tahunAjaran
            ],
            [
                'total_sks' => $totalSks,
                'approval' => '0' // Reset approval status to pending
            ]
        );
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
    public function getCourses(Request $request)
    {
        try {
            $semester = $request->input('semester');
            $search = $request->input('search');

            $query = JadwalKuliah::with(['matakuliah', 'ruangKelas'])
                ->orderBy('hari')
                ->orderBy('jam_mulai');

            // Apply semester filter
            if ($semester) {
                $query->where('plot_semester', $semester);
            }

            // Apply search filter
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('matakuliah', function ($q) use ($search) {
                        $q->where('nama_mk', 'like', "%{$search}%")
                            ->orWhere('kodemk', 'like', "%{$search}%");
                    });
                });
            }

            $courses = $query->get();

            return response()->json($courses);
        } catch (\Exception $e) {
            Log::error('Error in getCourses: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat memuat data'], 500);
        }
    }
}
