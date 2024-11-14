<?php

namespace App\Http\Controllers;

use App\Models\JadwalKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
    public function akademisi()
    {
        return view('mahasiswa/akademisi');
    }
    public function herreg()
    {
        return view('mahasiswa/herreg');
    }
    public function biaya()
    {
        return view('mahasiswa/biaya');
    }
    public function kulon()
    {
        return view('mahasiswa/kulon');
    }
}

//     public function buatirs(Request $request)
//     {
//         try {
//             // Get semester filter
//             $semester = $request->input('semester');

//             // Base query with relationships
//             $query = JadwalKuliah::with(['mataKuliah', 'ruangKelas', 'pembimbingakd'])
//                 ->orderBy('jam_mulai');

//             // Apply semester filter if selected
//             if ($semester) {
//                 $query->where('plot_semester', $semester);
//             }

//             $jadwalKuliah = $query->get();

//             // Create time slots from 07:00 to 17:00
//             $timeSlots = [];
//             $startTime = Carbon::createFromTime(7, 0);
//             $endTime = Carbon::createFromTime(17, 0);

//             while ($startTime <= $endTime) {
//                 $timeSlots[] = $startTime->format('H:i');
//                 $startTime->addHour();
//             }

//             // Initialize schedule matrix
//             $scheduleMatrix = [];
//             foreach ($timeSlots as $time) {
//                 $scheduleMatrix[$time] = [
//                     'Senin' => [],
//                     'Selasa' => [],
//                     'Rabu' => [],
//                     'Kamis' => [],
//                     'Jumat' => [],
//                     'Sabtu' => []
//                 ];
//             }

//             // Populate matrix with schedules
//             foreach ($jadwalKuliah as $jadwal) {
//                 $timeSlot = Carbon::createFromFormat('H:i:s', $jadwal->jam_mulai)->format('H:i');

//                 // Only add to matrix if within our display time range
//                 if (isset($scheduleMatrix[$timeSlot][$jadwal->hari])) {
//                     // Calculate duration for card height
//                     $startTime = Carbon::createFromFormat('H:i:s', $jadwal->jam_mulai);
//                     $endTime = Carbon::createFromFormat('H:i:s', $jadwal->jam_selesai);
//                     $duration = $endTime->diffInMinutes($startTime);

//                     // Add duration to the jadwal object
//                     $jadwal->duration = $duration;
//                     $jadwal->rowspan = ceil($duration / 60); // For spanning multiple hour slots

//                     $scheduleMatrix[$timeSlot][$jadwal->hari][] = $jadwal;
//                 }
//             }

//             // Get stats for the view
//             $stats = [
//                 'total_courses' => $jadwalKuliah->count(),
//                 'courses_by_semester' => $jadwalKuliah->groupBy('plot_semester')
//                     ->map(function ($items) {
//                         return $items->count();
//                     }),
//                 'courses_by_day' => $jadwalKuliah->groupBy('hari')
//                     ->map(function ($items) {
//                         return $items->count();
//                     })
//             ];

//             return view('mahasiswa.buatirs', compact(
//                 'scheduleMatrix',
//                 'timeSlots',
//                 'stats',
//                 'semester'
//             ));
//         } catch (\Exception $e) {
//             Log::error('Error in buatirs: ' . $e->getMessage());
//             return back()->with('error', 'Terjadi kesalahan saat memuat jadwal.');
//         }
//     }

//     // Add this helper method for schedule conflicts
//     private function isScheduleOverlap($existingStart, $existingEnd, $newStart, $newEnd)
//     {
//         return ($newStart < $existingEnd && $newEnd > $existingStart);
//     }
// }
