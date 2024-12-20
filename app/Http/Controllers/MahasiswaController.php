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

    private function checkModificationPeriod($irs)
    {
        $createdDate = Carbon::parse($irs->created_at);
        $now = Carbon::now();
        $daysSinceCreation = $createdDate->diffInDays($now);

        return $daysSinceCreation <= 14; // 14 hari = 2 minggu
    }

    private function checkCancellationPeriod($irs)
    {
        $approvalDate = Carbon::parse($irs->updated_at);
        $now = Carbon::now();
        $daysSinceApproval = $approvalDate->diffInDays($now);

        return $daysSinceApproval <= 28; // 28 hari = 4 minggu
    }

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

            if ($existingIrs) {
                $canModify = true;
                $canCancel = false;
                $periodExpired = false;

                if ($existingIrs->approval === '1') {
                    // Cek periode pembatalan untuk IRS yang sudah disetujui
                    $approvalDate = strtotime($existingIrs->updated_at);
                    $now = time();
                    $fourWeeks = 28 * 24 * 60 * 60;
                    $canCancel = ($now - $approvalDate) <= $fourWeeks;
                } else {
                    // Cek periode modifikasi untuk IRS yang belum disetujui
                    $creationDate = strtotime($existingIrs->created_at);
                    $now = time();
                    $twoWeeks = 14 * 24 * 60 * 60;
                    $periodExpired = ($now - $creationDate) > $twoWeeks;
                    $canModify = !$periodExpired;
                }

                if ($existingIrs->approval === '1' || $periodExpired) {
                    return view('mahasiswa.akademikMhs.buatIrs', [
                        'existingIrs' => $existingIrs,
                        'canCancel' => $canCancel,
                        'periodExpired' => $periodExpired
                    ]);
                }
            }


            // Jika belum ada IRS atau belum disetujui, lanjutkan dengan kode yang ada
            $mhsProdiId = DB::table('mahasiswa')
                ->where('nim', $mahasiswa->nim)
                ->value('prodi_id');

            $semesterSekarang = DB::table('mahasiswa')
                ->where('nim', $mahasiswa->nim)
                ->value('semester');

            $semesterYangDitampilkan = [];
            if ($semesterSekarang % 2 == 1) {
                // Jika semester ganjil, tampilkan semester 1, 3, 5, 7
                $semesterYangDitampilkan = [1, 3, 5, 7];
            } else {
                // Jika semester genap, tampilkan semester 2, 4, 6, 8
                $semesterYangDitampilkan = [2, 4, 6, 8];
            }

            $semester = $request->input('semester');
            $query = JadwalKuliah::with(['prodi', 'matakuliah', 'ruangKelas'])
                ->where('prodi_id', $mhsProdiId)
                ->whereIn('plot_semester', $semesterYangDitampilkan)
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

        $semesterSekarang = DB::table('mahasiswa')
            ->where('nim', $mahasiswa->nim)
            ->value('semester');

        $semesterYangDitampilkan = [];
        if ($semesterSekarang % 2 == 1) {
            // Jika semester ganjil, tampilkan semester 1, 3, 5, 7
            $semesterYangDitampilkan = [1, 3, 5, 7];
        } else {
            // Jika semester genap, tampilkan semester 2, 4, 6, 8
            $semesterYangDitampilkan = [2, 4, 6, 8];
        }

        // Get all jadwal that are approved
        $jadwalKuliah = JadwalKuliah::with(['matakuliah', 'ruangKelas'])
            ->where('prodi_id', $mhsProdiId)
            ->whereIn('plot_semester', $semesterYangDitampilkan)
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

    public function saveIrs(Request $request)
    {
        try {
            DB::beginTransaction();

            $mahasiswa = Auth::user();
            $selectedJadwals = $request->input('jadwals', []);
            $mahasiswaSemester = Mahasiswa::where('nim', $mahasiswa->nim)
                ->value('semester');

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
                $existingIrs->semester = $mahasiswaSemester;
                $existingIrs->total_sks = $totalSks;
                $existingIrs->approval = '0';
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
                $irs->semester = $mahasiswaSemester;
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
