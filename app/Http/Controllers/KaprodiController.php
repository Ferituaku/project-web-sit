<?php

namespace App\Http\Controllers;

use App\Models\JadwalKuliah;
use App\Models\Matakuliah;
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
        $dosen = User::whereIn('role', ['dosen', 'dekan', 'kaprodi'])->get();

        // Get all schedules with their relationships
        $jadwalKuliah = JadwalKuliah::with(['ruangkelas', 'matakuliah', 'dosen'])
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
            $request->validate([
                'ruangkelas_id' => 'required|exists:ruangkelas,koderuang',
                'kodemk' => 'required|exists:matakuliah,kodemk',
                'dosen_id' => 'required|exists:users,id',
                'plot_semester' => 'required|integer|min:1|max:8',
                'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
                'jam_mulai' => 'required|date_format:H:i',
            ]);

            // Find the course to get SKS
            $mataKuliah = Matakuliah::where('kodemk', $request->kodemk)->firstOrFail();

            // Calculate end time based on SKS
            $jamMulai = Carbon::createFromFormat('H:i', $request->jam_mulai);
            $jamSelesai = clone $jamMulai;

            switch ($mataKuliah->sks) {
                case 2:
                    $jamSelesai->addHours(1)->addMinutes(40); // 2 SKS = 100 minutes
                    break;
                case 3:
                    $jamSelesai->addHours(2)->addMinutes(30); // 3 SKS = 150 minutes
                    break;
                case 4:
                    $jamSelesai->addHours(3)->addMinutes(20); // 4 SKS = 200 minutes
                    break;
                default:
                    $jamSelesai->addHours(1); // Default 1 hour
            }

            // Check for schedule conflicts
            $conflicts = JadwalKuliah::where('hari', $request->hari)
                ->where(function ($query) use ($request) {
                    // Check room conflicts
                    $query->where('ruangkelas_id', $request->ruangkelas_id);
                    // Or check lecturer conflicts
                    $query->orWhere('dosen_id', $request->dosen_id);
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

            // Create new schedule
            $jadwal = JadwalKuliah::create([
                'ruangkelas_id' => $request->ruangkelas_id,
                'kodemk' => $request->kodemk,
                'dosen_id' => $request->dosen_id,
                'plot_semester' => $request->plot_semester,
                'hari' => $request->hari,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $jamSelesai->format('H:i')
            ]);
            DB::commit();

            // Log the successful creation
            Log::info('Jadwal created successfully', ['jadwal_id' => $jadwal->id]);


            return back()->with('success', 'Jadwal berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating jadwal: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan jadwal. ' . $e->getMessage());
        }
    }

    public function updateJadwal(Request $request, $id)
    {
        // Find the schedule
        $jadwal = JadwalKuliah::findOrFail($id);

        // Validate request
        $request->validate([
            'ruangkelas_id' => 'required|exists:ruangkelas,koderuang',
            'kodemk' => 'required|exists:matakuliah,kodemk',
            'dosen_id' => 'required|exists:users,id',
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
