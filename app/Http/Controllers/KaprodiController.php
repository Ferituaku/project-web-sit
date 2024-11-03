<?php

namespace App\Http\Controllers;

use App\Models\JadwalKuliah;
use App\Models\Matakuliah;
use App\Models\PembimbingAkd;
use App\Models\RuangKelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function buatjadwal()
    {
        // Get approved classrooms
        $ruangKelas = RuangKelas::where('approval', true)->get();

        // Get all courses
        $matakuliah = Matakuliah::all();

        // Get users with specific roles
        $dosen = PembimbingAkd::all();

        // Get all schedules with their relationships
        $jadwalKuliah = JadwalKuliah::with(['ruangKelas', 'mataKuliah', 'pembimbingakd'])
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        // Generate time slots from 07:00 to 20:00
        $timeSlots = [];
        $mulai = Carbon::createFromTime(7, 0);
        $selesai = Carbon::createFromTime(20, 0);

        while ($mulai <= $selesai) {
            $timeSlots[] = $mulai->format('H:i');
            $mulai->addMinutes(30);
        }

        // Initialize schedule matrix for each day
        $jadwalMatrix = [
            'Senin' => [],
            'Selasa' => [],
            'Rabu' => [],
            'Kamis' => [],
            'Jumat' => [],
            'Sabtu' => []
        ];

        // Populate matrix with existing schedules
        foreach ($jadwalKuliah as $jadwal) {
            $jadwalMatrix[$jadwal->hari][] = $jadwal;
        }

        return view('kaprodi.buatjadwal', compact(
            'ruangKelas',
            'matakuliah',
            'dosen',
            'timeSlots',
            'jadwalMatrix',
            'jadwalKuliah'
        ));
    }

    public function simpanJadwal(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validate request
            $validated = $request->validate([
                'ruangkelas_id' => 'required|exists:ruangkelas,koderuang',
                'kodemk' => 'required|exists:matakuliah,kodemk',
                'dosen_id' => 'required|exists:pembimbingakd,nip',
                'plot_semester' => 'required|integer|min:1|max:8',
                'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
                'jam_mulai' => 'required|date_format:H:i',
            ]);
            // Find the course to get SKS
            $mataKuliah = Matakuliah::where('kodemk', $request->kodemk)->firstOrFail();

            // Calculate end time based on SKS
            $jamMulai = Carbon::createFromFormat('H:i', $request->jam_mulai);
            $jamSelesai = clone $jamMulai;

            // Perhitungan durasi berdasarkan SKS
            $durasiMenit = $mataKuliah->sks * 50; // 50 menit per SKS
            $jamSelesai->addMinutes($durasiMenit);

            // Cek konflik jadwal
            $conflicts = $this->checkScheduleConflicts(
                $validated['hari'],
                $validated['ruangkelas_id'],
                $validated['dosen_id'],
                $validated['jam_mulai'],
                $jamSelesai->format('H:i')
            );

            if ($conflicts) {
                DB::rollBack();
                return back()
                    ->withInput()
                    ->with('error', 'Terdapat konflik jadwal. Silakan pilih waktu lain.');
            }

            // Create new schedule
            $jadwal = new JadwalKuliah();
            $jadwal->ruangkelas_id = $validated['ruangkelas_id'];
            $jadwal->kodemk = $validated['kodemk'];
            $jadwal->dosen_id = $validated['dosen_id'];
            $jadwal->plot_semester = $validated['plot_semester'];
            $jadwal->hari = $validated['hari'];
            $jadwal->jam_mulai = $validated['jam_mulai'];
            $jadwal->jam_selesai = $jamSelesai->format('H:i');
            $jadwal->save();


            DB::commit();
            return redirect()
                ->route('kaprodi.buatjadwal')
                ->with('success', 'Jadwal berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating jadwal: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan jadwal. ' . $e->getMessage());
        }
    }

    private function checkScheduleConflicts($hari, $ruangkelasId, $dosenId, $jamMulai, $jamSelesai)
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
            })->exists();
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

        return back()->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function deleteJadwal($id)
    {
        // Find and delete the schedule
        JadwalKuliah::findOrFail($id)->delete();
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }
}
