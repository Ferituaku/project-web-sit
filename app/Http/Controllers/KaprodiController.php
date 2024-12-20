<?php

namespace App\Http\Controllers;

use App\Models\JadwalKuliah;
use App\Models\Matakuliah;
use App\Models\PembimbingAkd;
use App\Models\ProgramStudi;
use App\Models\RuangKelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class KaprodiController extends Controller
{
    public function kaprodi()
    {
        return view('kaprodi.dashboard');
    }

    public function pilihmenu()
    {
        return view('/pilihmenu');
    }

    public function dosen()
    {
        return view('dosen/dashboard');
    }

    public function buatmatakuliah()
    {
        // Get the logged-in user's prodi_id from pembimbingakd table
        $kaprodiProdiId = DB::table('pembimbingakd')
            ->where('nip', Auth::user()->nip)
            ->value('prodi_id');

        // Get program studi data
        $programStudi = ProgramStudi::where('id', $kaprodiProdiId)->get();

        // Get mata kuliah for this specific prodi
        $mataKuliah = Matakuliah::with('prodi')
            ->where('prodi_id', $kaprodiProdiId)
            ->paginate(10);

        return view('kaprodi.buatmatakuliah', compact('programStudi', 'mataKuliah'));
    }

    public function buatmatkul(Request $request)
    {
        // Get kaprodi's prodi_id
        $kaprodiProdiId = DB::table('pembimbingakd')
            ->where('nip', Auth::user()->nip)
            ->value('prodi_id');

        $validator = Validator::make($request->all(), [
            'kodemk' => 'required|integer|unique:matakuliah,kodemk',
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:4',
            'semester' => 'required|integer|min:1|max:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Automatically set prodi_id to kaprodi's prodi
            Matakuliah::create([
                'kodemk' => $request->kodemk,
                'nama_mk' => $request->nama_mk,
                'sks' => $request->sks,
                'semester' => $request->semester,
                'prodi_id' => $kaprodiProdiId // Set prodi_id from kaprodi
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Mata kuliah berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan mata kuliah: ' . $e->getMessage()
            ], 500);
        }
    }

    public function editmatkul($kodemk)
    {
        $kaprodiProdiId = DB::table('pembimbingakd')
            ->where('nip', Auth::user()->nip)
            ->value('prodi_id');

        try {
            $mataKuliah = Matakuliah::with('prodi')
                ->where('kodemk', $kodemk)
                ->where('prodi_id', $kaprodiProdiId) // Only allow editing matkul from same prodi
                ->firstOrFail();

            $programStudi = ProgramStudi::where('id', $kaprodiProdiId)->get();

            return response()->json([
                'status' => 'success',
                'matakuliah' => $mataKuliah,
                'programStudi' => $programStudi
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mata kuliah tidak ditemukan'
            ], 404);
        }
    }

    public function updateMatakuliah(Request $request, $kodemk)
    {
        $kaprodiProdiId = DB::table('pembimbingakd')
            ->where('nip', Auth::user()->nip)
            ->value('prodi_id');

        $validator = Validator::make($request->all(), [
            'new_kodemk' => 'required|integer|unique:matakuliah,kodemk,' . $kodemk . ',kodemk',
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:4',
            'semester' => 'required|integer|min:1|max:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $mataKuliah = Matakuliah::where('kodemk', $kodemk)
                ->where('prodi_id', $kaprodiProdiId) // Only allow updating matkul from same prodi
                ->firstOrFail();

            $mataKuliah->update([
                'kodemk' => $request->new_kodemk,
                'nama_mk' => $request->nama_mk,
                'sks' => $request->sks,
                'semester' => $request->semester,
                'prodi_id' => $kaprodiProdiId // Maintain same prodi_id
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Mata kuliah berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui mata kuliah: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyMatakuliah($kodemk)
    {
        $kaprodiProdiId = DB::table('pembimbingakd')
            ->where('nip', Auth::user()->nip)
            ->value('prodi_id');

        try {
            $mataKuliah = Matakuliah::where('kodemk', $kodemk)
                ->where('prodi_id', $kaprodiProdiId) // Only allow deleting matkul from same prodi
                ->firstOrFail();

            // Check if there are any related records in jadwal_kuliah
            if ($mataKuliah->jadwalKuliah()->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak dapat menghapus mata kuliah karena masih terdapat jadwal kuliah terkait'
                ], 422);
            }

            $mataKuliah->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Mata kuliah berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus mata kuliah: ' . $e->getMessage()
            ], 500);
        }
    }
    public function buatjadwal()
    {
        // Ambil prodi_id kaprodi yang login
        $prodiId = DB::table('pembimbingakd')->where('nip', Auth::user()->nip)->value('prodi_id');

        // Filter data sesuai prodi_id
        $program_studi = ProgramStudi::all();
        $ruangKelas = RuangKelas::where('program_studi_id', $prodiId)
            ->where('approval', '1')
            ->get();
        $matakuliah = Matakuliah::where('prodi_id', $prodiId)->get();
        $dosen = PembimbingAkd::all(); // Tidak perlu filter

        $jadwalKuliah = JadwalKuliah::with(['ruangKelas', 'mataKuliah', 'pembimbingakd'])
            ->where('prodi_id', $prodiId)
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->paginate(10);

        // Generate timeslots seperti biasa
        $timeSlots = [];
        $mulai = Carbon::createFromTime(7, 0);
        $selesai = Carbon::createFromTime(20, 0);
        while ($mulai <= $selesai) {
            $timeSlots[] = $mulai->format('H:i');
            $mulai->addMinutes(30);
        }

        return view('kaprodi.buatjadwal', compact(
            'ruangKelas',
            'matakuliah',
            'dosen',
            'timeSlots',
            'jadwalKuliah',
            'program_studi'
        ));
    }

    public function simpanJadwal(Request $request)
    {
        try {
            DB::beginTransaction();

            $baseValidation = [
                'prodi_id' => 'required|exists:program_studi,id',
                'kodemk' => 'required|exists:matakuliah,kodemk',
                'dosen_id' => 'required|exists:pembimbingakd,nip',
                'plot_semester' => 'required|integer|min:1|max:8',
            ];

            // Validate base data
            $request->validate($baseValidation);

            // Find the course to get SKS
            $mataKuliah = Matakuliah::where('kodemk', $request->kodemk)->firstOrFail();

            // Get the number of groups from the request
            $groupCount = $request->input('group_count', 3);

            // Validate and create schedules for each class group
            for ($i = 1; $i <= $groupCount; $i++) {
                $group = chr(64 + $i); // Convert number to letter (1 = A, 2 = B, etc.)

                $request->validate([
                    "hari_{$group}" => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
                    "jam_mulai_{$group}" => 'required|date_format:H:i',
                    "ruangkelas_id_{$group}" => 'required|exists:ruangkelas,koderuang',
                ]);

                $jamMulai = Carbon::createFromFormat('H:i', $request->{"jam_mulai_{$group}"});
                $jamSelesai = clone $jamMulai;
                $durasiMenit = $mataKuliah->sks * 50;
                $jamSelesai->addMinutes($durasiMenit);

                // Check for conflicts
                $conflicts = $this->checkScheduleConflicts(
                    $request->{"hari_{$group}"},
                    $request->{"ruangkelas_id_{$group}"},
                    $request->dosen_id,
                    $request->{"jam_mulai_{$group}"},
                    $jamSelesai->format('H:i'),
                    $request->prodi_id
                );

                if ($conflicts) {
                    DB::rollBack();
                    return back()
                        ->withInput()
                        ->with('error', "Terdapat konflik jadwal untuk kelas {$group}. Silakan pilih waktu lain.");
                }

                // Create new schedule for this class group
                $jadwal = new JadwalKuliah();
                $jadwal->prodi_id = $request->prodi_id;
                $jadwal->ruangkelas_id = $request->{"ruangkelas_id_{$group}"};
                $jadwal->kodemk = $request->kodemk;
                $jadwal->dosen_id = $request->dosen_id;
                $jadwal->plot_semester = $request->plot_semester;
                $jadwal->class_group = $group;
                $jadwal->hari = $request->{"hari_{$group}"};
                $jadwal->jam_mulai = $request->{"jam_mulai_{$group}"};
                $jadwal->jam_selesai = $jamSelesai->format('H:i');
                $jadwal->save();
            }

            DB::commit();
            return redirect()
                ->route('kaprodi.buatjadwal')
                ->with('success', 'Jadwal untuk semua kelas berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating jadwal: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan jadwal. ' . $e->getMessage());
        }
    }

    private function checkScheduleConflicts($hari, $ruangkelasId, $dosenId, $jamMulai, $jamSelesai, $prodiId)
    {
        return JadwalKuliah::where('hari', $hari)
            ->where(function ($query) use ($ruangkelasId, $dosenId) {
                $query->where('ruangkelas_id', $ruangkelasId)
                    ->orWhere('dosen_id', $dosenId);
            })
            ->where(function ($query) use ($jamMulai, $jamSelesai) {
                $query->whereBetween('jam_mulai', [$jamMulai, $jamSelesai])
                    ->orWhereBetween('jam_selesai', [$jamMulai, $jamSelesai])
                    ->orWhere(function ($q) use ($jamMulai, $jamSelesai) {
                        $q->where('jam_mulai', '<=', $jamMulai)
                            ->where('jam_selesai', '>=', $jamSelesai);
                    });
            })
            ->where('prodi_id', $prodiId)
            ->exists();
    }

    public function updateJadwal(Request $request, $id)
    {
        // Find the schedule
        $jadwal = JadwalKuliah::findOrFail($id);

        // Validate request
        $request->validate([
            'ruangkelas_id' => 'required|exists:ruangkelas,koderuang',
            'kodemk' => 'required|exists:matakuliah,kodemk',
            'dosen_id' => 'required|exists:pembimbingakd,nip',
            'plot_semester' => 'required|integer|min:1|max:8',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
        ]);

        // Find the course to get SKS
        $matakuliah = Matakuliah::where('kodemk', $request->kodemk)->firstOrFail();

        // Calculate end time based on SKS
        $jamMulai = Carbon::createFromFormat('H:i', $request->jam_mulai);
        $jamSelesai = clone $jamMulai;

        switch ($matakuliah->sks) {
            case 2:
                $jamSelesai->addHours(1)->addMinutes(40);
                break;
            case 3:
                $jamSelesai->addHours(2)->addMinutes(30);
                break;
            case 4:
                $jamSelesai->addHours(3)->addMinutes(20);
                break;
            default:
                $jamSelesai->addHours(1);
        }

        // Check for schedule conflicts (excluding current schedule)
        $conflicts = JadwalKuliah::where('id', '!=', $id)
            ->where('hari', $request->hari)
            ->where(function ($query) use ($request) {
                $query->where('ruangkelas_id', $request->ruangkelas_id)
                    ->orWhere('dosen_id', $request->dosen_id);
            })
            ->where(function ($query) use ($request, $jamSelesai) {
                $query->whereBetween('jam_mulai', [$request->jam_mulai, $jamSelesai->format('H:i')])
                    ->orWhereBetween('jam_selesai', [$request->jam_mulai, $jamSelesai->format('H:i')])
                    ->orWhere(function ($q) use ($request, $jamSelesai) {
                        $q->where('jam_mulai', '<=', $request->jam_mulai)
                            ->where('jam_selesai', '>=', $jamSelesai->format('H:i'));
                    });
            })->exists();

        if ($conflicts) {
            return back()
                ->withInput()
                ->with('error', 'Terdapat konflik jadwal pada waktu, ruangan, atau dosen yang dipilih.');
        }

        // Update the schedule
        $jadwal->update([
            'ruangkelas_id' => $request->ruangkelas_id,
            'kodemk' => $request->kodemk,
            'dosen_id' => $request->dosen_id,
            'plot_semester' => $request->plot_semester,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $jamSelesai->format('H:i')
        ]);

        $jadwal->update($request->all());
        return redirect()->route('kaprodi.jadwal.update')->with('success', 'Jadwal berhasil diperbarui.');
        // return back()->with('success', 'Jadwal berhasil diperbarui.');


    }

    public function deleteJadwal($id)
    {
        // Find and delete the schedule
        JadwalKuliah::findOrFail($id)->delete();
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }

    public function daftarJadwal(Request $request)
    {
        try {
            // Get kaprodi's prodi_id
            $prodiId = DB::table('pembimbingakd')->where('nip', Auth::user()->nip)->value('prodi_id');

            // Get semester filter
            $semester = $request->input('semester');

            // Base query with relationships, filtered by prodi_id
            $query = JadwalKuliah::with(['mataKuliah', 'ruangKelas', 'pembimbingakd'])
                ->where('prodi_id', $prodiId)
                ->orderBy('jam_mulai');

            // Apply semester filter if selected
            if ($semester) {
                $query->where('plot_semester', $semester);
            }

            $jadwalKuliah = $query->get();

            // Create time slots from 07:00 to 17:00
            $timeSlots = [];
            $startTime = Carbon::createFromTime(7, 0);
            $endTime = Carbon::createFromTime(17, 0);

            while ($startTime <= $endTime) {
                $timeSlots[] = $startTime->format('H:i');
                $startTime->addHour();
            }

            // Initialize schedule matrix
            $scheduleMatrix = [];
            foreach ($timeSlots as $time) {
                $scheduleMatrix[$time] = [
                    'Senin' => [],
                    'Selasa' => [],
                    'Rabu' => [],
                    'Kamis' => [],
                    'Jumat' => [],
                    'Sabtu' => []
                ];
            }

            // Populate matrix with schedules
            foreach ($jadwalKuliah as $jadwal) {
                $timeSlot = Carbon::createFromFormat('H:i:s', $jadwal->jam_mulai)->format('H:i');

                // Only add to matrix if within our display time range
                if (isset($scheduleMatrix[$timeSlot][$jadwal->hari])) {
                    // Calculate duration for card height
                    $startTime = Carbon::createFromFormat('H:i:s', $jadwal->jam_mulai);
                    $endTime = Carbon::createFromFormat('H:i:s', $jadwal->jam_selesai);
                    $duration = $endTime->diffInMinutes($startTime);

                    // Add duration to the jadwal object
                    $jadwal->duration = $duration;
                    $jadwal->rowspan = ceil($duration / 60); // For spanning multiple hour slots

                    $scheduleMatrix[$timeSlot][$jadwal->hari][] = $jadwal;
                }
            }

            // Get stats for the view
            $stats = [
                'total_courses' => $jadwalKuliah->count(),
                'courses_by_semester' => $jadwalKuliah->groupBy('plot_semester')
                    ->map(function ($items) {
                        return $items->count();
                    }),
                'courses_by_day' => $jadwalKuliah->groupBy('hari')
                    ->map(function ($items) {
                        return $items->count();
                    })
            ];

            return view('kaprodi.daftarJadwal', compact(
                'scheduleMatrix',
                'timeSlots',
                'stats',
                'semester'
            ));
        } catch (\Exception $e) {
            Log::error('Error in daftarJadwal: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat jadwal.');
        }
    }

    // Add this helper method for schedule conflicts
    private function isScheduleOverlap($existingStart, $existingEnd, $newStart, $newEnd)
    {
        return ($newStart < $existingEnd && $newEnd > $existingStart);
    }
}
