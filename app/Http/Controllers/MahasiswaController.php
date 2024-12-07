<?php

namespace App\Http\Controllers;

use App\Models\Irs;
use App\Models\JadwalKuliah;
use App\Models\Mahasiswa;
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
        try {
            $mahasiswa = Auth::user();

            // Get all IRS records for the student with eager loading
            $irsRecords = Irs::where('nim', $mahasiswa->nim)
                ->with(['jadwalKuliah.matakuliah', 'jadwalKuliah.pembimbingakd'])
                ->orderBy('tahun_ajaran', 'desc')
                ->orderBy('semester', 'desc')
                ->get()
                ->groupBy(function ($item) use ($mahasiswa) {
                    return sprintf(
                        'SMT %d - %s - %s',
                        $mahasiswa->semester,
                        $item->semester % 2 == 1 ? 'Ganjil' : 'Genap',
                        $item->tahun_ajaran
                    );
                });

            return view('mahasiswa.akademikMhs.hasilirs', compact('irsRecords', 'mahasiswa'));
        } catch (\Exception $e) {
            Log::error('Error in hasilirs:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    public function khs()
    {
        return view('mahasiswa.akademikMhs.khs');
    }

    public function transkrip()
    {
        return view('mahasiswa.akademikMhs.transkrip');
    }

    // public function buatIrs(Request $request)
    // {
    //     try {
    //         // Ambil data mahasiswa yang sedang login
    //         $mahasiswa = Auth::user();

    //         // Dapatkan prodi_id mahasiswa dari tabel mahasiswa
    //         $mhsProdiId = DB::table('mahasiswa')
    //             ->where('nim', $mahasiswa->nim)
    //             ->value('prodi_id');

    //         // Ambil input semester dari request
    //         $semester = $request->input('semester');

    //         // Ambil jadwal kuliah sesuai prodi_id mahasiswa
    //         $query = JadwalKuliah::with(['prodi', 'matakuliah', 'ruangKelas'])
    //             ->where('prodi_id', $mhsProdiId) // Filter berdasarkan prodi_id mahasiswa
    //             ->orderBy('hari')
    //             ->orderBy('jam_mulai');

    //         if ($semester) {
    //             $query->where('plot_semester', $semester); // Filter berdasarkan semester jika tersedia
    //         }

    //         $jadwalKuliah = $query->get();

    //         // Ambil IRS saat ini jika ada
    //         $currentIrs = Irs::where('nim', $mahasiswa->nim)->first();

    //         // Ambil semua jadwal ID yang telah dipilih (jika ada)
    //         $selectedJadwalIds = [];
    //         if ($currentIrs) {
    //             $selectedJadwalIds = $currentIrs->jadwalKuliah->pluck('id')->toArray();
    //         }

    //         // Buat matriks jadwal berdasarkan slot waktu
    //         $timeSlots = $this->createTimeSlots();
    //         $scheduleMatrix = $this->createScheduleMatrix($timeSlots, $jadwalKuliah);

    //         // Jika request berupa JSON, kembalikan data jadwal sebagai JSON
    //         if ($request->expectsJson()) {
    //             return response()->json($jadwalKuliah);
    //         }

    //         // Kembalikan view dengan data yang dibutuhkan
    //         return view('mahasiswa.akademikMhs.buatIrs', compact(
    //             'scheduleMatrix',
    //             'jadwalKuliah',
    //             'timeSlots',
    //             'selectedJadwalIds',
    //             'currentIrs',
    //             'semester'
    //         ));
    //     } catch (\Exception $e) {
    //         // Jika terjadi error, tangani dan berikan respons sesuai format request
    //         if ($request->expectsJson()) {
    //             return response()->json(['error' => $e->getMessage()], 500);
    //         }
    //         return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    //     }
    // }



    public function buatIrs(Request $request)
    {
        try {
            // Ambil data mahasiswa yang sedang login
            $mahasiswa = Auth::user();

            // Set tahun ajaran berdasarkan bulan saat ini
            $currentYear = date('Y');
            $month = date('n');
            $tahunAjaran = $month >= 8 ?
                $currentYear . '/' . ($currentYear + 1) : ($currentYear - 1) . '/' . $currentYear;

            // Cek apakah sudah ada IRS untuk tahun ajaran ini
            $existingIrs = Irs::where('nim', $mahasiswa->nim)
                ->where('tahun_ajaran', $tahunAjaran)
                ->first();

            // Cek apakah saat ini adalah periode pengisian IRS
            $isIrsPeriod = true; // Logika pengecekan periode IRS bisa disesuaikan
            if (!$isIrsPeriod && !$existingIrs) {
                return view('mahasiswa.akademikMhs.buatIrs', [
                    'noIrsPeriod' => true
                ]);
            }

            // Jika IRS sudah ada dan disetujui, tampilkan status
            if ($existingIrs && $existingIrs->approval === '1') {
                return view('mahasiswa.akademikMhs.buatIrs', [
                    'existingIrs' => $existingIrs
                ]);
            }

            // Jika belum ada IRS atau belum disetujui, lanjutkan dengan kode yang ada
            $mhsProdiId = DB::table('mahasiswa')
                ->where('nim', $mahasiswa->nim)
                ->value('prodi_id');

            // Kode yang sudah ada untuk mengambil data jadwal dll
            $semester = $request->input('semester');
            $query = JadwalKuliah::with(['prodi', 'matakuliah', 'ruangKelas'])
                ->where('prodi_id', $mhsProdiId)
                ->orderBy('hari')
                ->orderBy('jam_mulai');

            if ($semester) {
                $query->where('plot_semester', $semester);
            }

            $jadwalKuliah = $query->get();

            // Ambil jadwal yang sudah dipilih jika ada
            $selectedJadwalIds = [];
            if ($existingIrs) {
                $selectedJadwalIds = DB::table('irs_jadwal')
                    ->where('irs_id', $existingIrs->id)
                    ->pluck('jadwal_id')
                    ->toArray();
            }

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
                'existingIrs',
                'semester'
            ));
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }




    // public function buatIrs(Request $request)
    // {
    //     try {
    //         $mahasiswa = Auth::user();
    //         $semester = $request->input('semester');

    //         // Get current IRS if exists
    //         $currentIrs = Irs::where('nim', $mahasiswa->nim)
    //             ->orderBy('created_at', 'desc')
    //             ->first();

    //         // If no IRS exists, create an empty one with approval = 0
    //         if (!$currentIrs) {
    //             $currentIrs = new Irs();
    //             $currentIrs->approval = '0';
    //         }

    //         // Get jadwal data only if IRS is not approved
    //         if ($currentIrs->approval == '0') {
    //             $jadwalKuliah = JadwalKuliah::with(['matakuliah', 'ruangKelas'])
    //                 ->orderBy('hari')
    //                 ->orderBy('jam_mulai')
    //                 ->get();

    //             // Get all selected jadwal IDs
    //             $selectedJadwalIds = [];
    //             if ($currentIrs->id) {
    //                 $selectedJadwalIds = $currentIrs->jadwalKuliah->pluck('id')->toArray();
    //             }

    //             $query = JadwalKuliah::with(['mataKuliah', 'ruangKelas', 'pembimbingakd'])
    //                 ->orderBy('jam_mulai');

    //             if ($semester) {
    //                 $query->where('plot_semester', $semester);
    //             }

    //             $jadwalKuliah = $query->get();

    //             // Create time matrix
    //             $timeSlots = $this->createTimeSlots();
    //             $scheduleMatrix = $this->createScheduleMatrix($timeSlots, $jadwalKuliah);

    //             if ($request->expectsJson()) {
    //                 return response()->json($jadwalKuliah);
    //             }

    //             return view('mahasiswa.akademikMhs.buatIrs', compact(
    //                 'scheduleMatrix',
    //                 'jadwalKuliah',
    //                 'timeSlots',
    //                 'selectedJadwalIds',
    //                 'currentIrs',
    //                 'semester'
    //             ));
    //         }

    //         // If IRS is approved, redirect with message
    //         return redirect()->back()->with('info', 'IRS Anda telah disetujui. Tidak dapat melakukan perubahan.');
    //     } catch (\Exception $e) {
    //         if ($request->expectsJson()) {
    //             return response()->json(['error' => $e->getMessage()], 500);
    //         }
    //         return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    //     }
    // }
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

        $mahasiswa = Auth::user();
        $mhsProdiId = DB::table('mahasiswa')
            ->where('nim', $mahasiswa->nim)
            ->value('prodi_id');

        // Get all jadwal that are approved
        $jadwalKuliah = JadwalKuliah::with(['matakuliah', 'ruangKelas'])
            ->where('prodi_id', $mhsProdiId)
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

    public function cetakIrs($tahunAjaran, $semester)
    {
        try {
            // Ambil user yang login
            $user = Auth::user();

            // Ambil data mahasiswa berdasarkan nim user, dengan relasi program studi
            $mahasiswa = Mahasiswa::with(['prodi', 'pembimbingAkd'])
                ->where('nim', $user->nim)
                ->firstOrFail();

            $irs = Irs::where('nim', $mahasiswa->nim)
                ->where('tahun_ajaran', $tahunAjaran)
                ->where('semester', $semester)
                ->with(['jadwalKuliah.matakuliah', 'jadwalKuliah.pembimbingakd'])
                ->firstOrFail();


            if ($irs->approval !== '1') {
                return back()->with('error', 'IRS belum disetujui dan tidak dapat dicetak');
            }

            $data = [
                'irs' => $irs,
                'mahasiswa' => $mahasiswa,
                'semester_text' => $semester % 2 == 1 ? 'Ganjil' : 'Genap'
            ];

            $pdf = PDF::loadView('mahasiswa.akademikMhs.cetak-irs', $data);
            $pdf->setPaper('A4', 'portrait');
            $filename = sprintf(
                'IRS-%s-%s-SMT%d.pdf',
                $mahasiswa->nim,
                str_replace('/', '-', $tahunAjaran),
                $semester
            );
            return $pdf->stream($filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mencetak IRS: ' . $e->getMessage());
        }
    }


    // public function saveIrs(Request $request)
    // {
    //     try {
    //         DB::beginTransaction();

    //         $mahasiswa = Auth::user();
    //         $selectedJadwals = $request->input('jadwals', []);

    //         // Validasi input
    //         if (empty($selectedJadwals)) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Pilih minimal satu mata kuliah'
    //             ], 400);
    //         }


    //         // Ambil semester dari jadwal kuliah yang dipilih pertama
    //         $jadwalKuliah = JadwalKuliah::find($selectedJadwals[0]);
    //         $semester = $jadwalKuliah->plot_semester; // Mengambil semester dari jadwal kuliah

    //         // Hitung total SKS
    //         $totalSks = JadwalKuliah::whereIn('id', $selectedJadwals)
    //             ->join('matakuliah', 'jadwalKuliah.kodemk', '=', 'matakuliah.kodemk')
    //             ->sum('matakuliah.sks');

    //         // Validasi batas SKS
    //         if ($totalSks > 24) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Total SKS melebihi batas maksimum (24 SKS)'
    //             ], 400);
    //         }

    //         // Cek konflik jadwal
    //         if ($this->hasScheduleConflict($selectedJadwals)) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Terdapat jadwal yang bertabrakan'
    //             ], 400);
    //         }

    //         // Set tahun ajaran
    //         $currentYear = date('Y');
    //         $month = date('n');
    //         if ($month >= 8) { // Semester Ganjil
    //             $tahunAjaran = $currentYear . '/' . ($currentYear + 1);
    //         } else { // Semester Genap
    //             $tahunAjaran = ($currentYear - 1) . '/' . $currentYear;
    //         }

    //         // Cari IRS yang sudah ada untuk semester dan tahun ajaran ini
    //         $existingIrs = Irs::where('nim', $mahasiswa->nim)
    //             ->where('semester', $semester)
    //             ->where('tahun_ajaran', $tahunAjaran)
    //             ->first();

    //         if ($existingIrs) {
    //             // Update IRS yang sudah ada
    //             $existingIrs->total_sks = $totalSks;
    //             $existingIrs->approval = '0'; // Reset status approval
    //             $existingIrs->save();

    //             // Hapus semua jadwal lama
    //             DB::table('irs_jadwal')->where('irs_id', $existingIrs->id)->delete();

    //             // Masukkan jadwal baru
    //             foreach ($selectedJadwals as $jadwalId) {
    //                 DB::table('irs_jadwal')->insert([
    //                     'irs_id' => $existingIrs->id,
    //                     'jadwal_id' => $jadwalId,
    //                     'created_at' => now(),
    //                     'updated_at' => now()
    //                 ]);
    //             }
    //         } else { // Buat record IRS baru
    //             $irs = new Irs();
    //             $irs->nim = $mahasiswa->nim;
    //             $irs->semester = $semester; // Menggunakan semester dari jadwal
    //             $irs->tahun_ajaran = $tahunAjaran;
    //             $irs->total_sks = $totalSks;
    //             $irs->approval = '0';
    //             $irs->save();

    //             // Attach jadwal yang dipilih ke IRS melalui tabel pivot
    //             foreach ($selectedJadwals as $jadwalId) {
    //                 DB::table('irs_jadwal')->insert([
    //                     'irs_id' => $irs->id,
    //                     'jadwal_id' => $jadwalId,
    //                     'created_at' => now(),
    //                     'updated_at' => now()
    //                 ]);
    //             }
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

            // Set tahun ajaran
            $currentYear = date('Y');
            $month = date('n');
            if ($month >= 8) {
                $tahunAjaran = $currentYear . '/' . ($currentYear + 1);
            } else {
                $tahunAjaran = ($currentYear - 1) . '/' . $currentYear;
            }

            // Ambil semester dari jadwal kuliah yang dipilih pertama
            $jadwalKuliah = JadwalKuliah::find($selectedJadwals[0]);
            $semester = $jadwalKuliah->plot_semester;

            // Cek IRS yang sudah ada
            $existingIrs = Irs::where('nim', $mahasiswa->nim)
                ->where('tahun_ajaran', $tahunAjaran)
                ->first();

            // Jika IRS sudah disetujui, tolak perubahan
            if ($existingIrs && $existingIrs->approval === '1') {
                return response()->json([
                    'success' => false,
                    'message' => 'IRS sudah disetujui dan tidak dapat diubah'
                ], 400);
            }

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

            if ($existingIrs) {
                // Update IRS yang sudah ada
                $existingIrs->total_sks = $totalSks;
                $existingIrs->save();

                // Hapus jadwal lama
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

                $message = 'IRS berhasil diperbarui dan menunggu persetujuan dosen wali';
            } else {
                // Buat IRS baru
                $irs = new Irs();
                $irs->nim = $mahasiswa->nim;
                $irs->semester = $semester;
                $irs->tahun_ajaran = $tahunAjaran;
                $irs->total_sks = $totalSks;
                $irs->approval = '0';
                $irs->save();

                foreach ($selectedJadwals as $jadwalId) {
                    DB::table('irs_jadwal')->insert([
                        'irs_id' => $irs->id,
                        'jadwal_id' => $jadwalId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                $message = 'IRS berhasil disimpan dan menunggu persetujuan dosen wali';
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message
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
