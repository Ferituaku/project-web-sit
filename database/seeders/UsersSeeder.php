<?php

namespace Database\Seeders;

use App\Models\JadwalKuliah;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\PembimbingAkd;
use App\Models\ProgramStudi;
use App\Models\RuangKelas;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programStudi = [
            [
                'id' => 1,
                'nama' => 'Informatika',
            ],
            [
                'id' => 2,
                'nama' => 'Statistika',
            ],
            [
                'id' => 3,
                'nama' => 'Matematika',
            ],
            [
                'id' => 4,
                'nama' => 'Biologi',
            ],
        ];
        foreach ($programStudi as $prodi) {
            ProgramStudi::create($prodi);
        }


        $ruangKelas = [
            [
                'koderuang' => 'A102',
                'kapasitas' => 40,
                'program_studi_id' => 1,

            ],
            [
                'koderuang' => 'A202',
                'kapasitas' => 30,
                'program_studi_id' => 1,

            ],
            [
                'koderuang' => 'E101',
                'kapasitas' => 50,
                'program_studi_id' => 1,
            ],
            [
                'koderuang' => 'B101',
                'kapasitas' => 30,
                'program_studi_id' => 1,
            ],
            [
                'koderuang' => 'B102',
                'kapasitas' => 35,
                'program_studi_id' => 2,
            ],
            [
                'koderuang' => 'B103',
                'kapasitas' => 30,
                'program_studi_id' => 1,
            ],
        ];
        foreach ($ruangKelas as $key => $val) {
            RuangKelas::create($val);
        }


        $matakuliah = [
            [

                'kodemk' => 1101,
                'prodi_id' => 1,
                'nama_mk' => 'Algoritma Pemrograman',
                'sks' => 4,
                'semester' => 2,
            ],
            [

                'kodemk' => 1102,
                'prodi_id' => 1,
                'nama_mk' => 'Basis Data',
                'sks' => 3,
                'semester' => 3,
            ],
            [

                'kodemk' => 1103,
                'prodi_id' => 1,
                'nama_mk' => 'Pengembangan Berbasis Platform',
                'sks' => 4,
                'semester' => 5,
            ],
            [

                'kodemk' => 1104,
                'prodi_id' => 1,
                'nama_mk' => 'Struktur Data',
                'sks' => 3,
                'semester' => 2,
            ],
            [

                'kodemk' => 1105,
                'prodi_id' => 1,
                'nama_mk' => 'Sistem Operasi',
                'sks' => 3,
                'semester' => 3,
            ],
            [

                'kodemk' => 1106,
                'prodi_id' => 1,
                'nama_mk' => 'Jaringan Komputer',
                'sks' => 2,
                'semester' => 4,
            ],
            [

                'kodemk' => 1107,
                'prodi_id' => 1,
                'nama_mk' => 'Sistem Cerdas',
                'sks' => 3,
                'semester' => 4,
            ],
            [

                'kodemk' => 1108,
                'prodi_id' => 1,
                'nama_mk' => 'Kewirausahaan',
                'sks' => 2,
                'semester' => 6,
            ]
        ];

        foreach ($matakuliah as $mk) {
            Matakuliah::create($mk);
        }


        $pembimbingAkd = [
            [
                'nip' => 12231301,
                'name' => 'Joyo Sujoyo S.Kom, M.Kom',
                'email' => 'joyo@lecturer.com',
                'dosen' => 1,
                'dekan' => 0,
                'kaprodi' => 0,
                'dosen_wali' => 1,
            ],
            [
                'nip' => 12231302,
                'name' => 'Diane S.Kom, M.Kom',
                'email' => 'diane@lecturer.com',
                'dosen' => 1,
                'dekan' => 0,
                'kaprodi' => 0,
                'dosen_wali' => 0,
            ],
            [
                'nip' => 12231303,
                'name' => 'Richard S.Kom, M.Kom',
                'email' => 'richard@lecturer.com',
                'dosen' => 1,
                'dekan' => 0,
                'kaprodi' => 0,
                'dosen_wali' => 1,
            ],
            [
                'nip' => 12231304,
                'name' => 'Jhonny S.Kom, M.Kom',
                'email' => 'jhonny@lecturer.com',
                'dosen' => 1,
                'dekan' => 0,
                'kaprodi' => 0,
                'dosen_wali' => 1,
            ],
            [
                'nip' => 12231305,
                'name' => 'Susi Susiastuti S.Kom, M.Kom',
                'email' => 'susi@lecturer.com',
                'dosen' => 1,
                'dekan' => 0,
                'kaprodi' => 0,
                'dosen_wali' => 1,
            ],
            [
                'nip' => 12231201,
                'name' => 'Dr. Antoni S.Kom, M. Si, P.Hd',
                'email' => 'antoni@lecturer.com',
                'dosen' => 1,
                'dekan' => 1,
                'kaprodi' => 0,
                'dosen_wali' => 0,
            ],
            [
                'nip' => 12231202,
                'name' => 'Budi Yono S.Kom, M.T',
                'email' => 'budi@lecturer.com',
                'dosen' => 1,
                'dekan' => 0,
                'kaprodi' => 1,
                'dosen_wali' => 0,
            ],

        ];
        foreach ($pembimbingAkd as $pakad) {
            PembimbingAkd::create($pakad);
        }

        $mahasiswa = [
            [
                'nim' => 240601221001,
                'name' => 'Alexander Vinsen',
                'email' => 'elvinsen@students.com',
                'dosen_id' => 12231303,
                'prodi_id' => 1,
                'Semester' => 5,
                'SKS' => 86,
                'tahun_ajaran' => '2024/2025',
            ],
            [
                'nim' => 240601221002,
                'name' => 'Chintya Kirana',
                'email' => 'kirana@students.com',
                'dosen_id' => 12231305,
                'prodi_id' => 1,
                'Semester' => 3,
                'SKS' => 64,
                'tahun_ajaran' => '2024/2025',
            ],


        ];
        foreach ($mahasiswa as $mhs) {
            Mahasiswa::create($mhs);
        }

        $jadwalKuliah = [
            [
                'prodi_id' => 1,
                'ruangkelas_id' => 'A102',
                'kodemk' => 1104,
                'dosen_id' => 12231301,
                'plot_semester' => 5,
                'class_group' => 'A',
                'hari' => 'Rabu',
                'jam_mulai' => '07:00:00',
                'jam_selesai' => '09:30:00',
                'approval' => '1',
                'rejection_reason' => null,
            ],
            [
                'prodi_id' => 1,
                'ruangkelas_id' => 'A202',
                'kodemk' => 1102,
                'dosen_id' => 12231303,
                'plot_semester' => 5,
                'class_group' => 'A',
                'hari' => 'Senin',
                'jam_mulai' => '07:00:00',
                'jam_selesai' => '09:30:00',
                'approval' => '1',
                'rejection_reason' => null,
            ],
            [
                'prodi_id' => 1,
                'ruangkelas_id' => 'E101',
                'kodemk' => 1105,
                'dosen_id' => 12231303,
                'plot_semester' => 5,
                'class_group' => 'A',
                'hari' => 'Kamis',
                'jam_mulai' => '07:00:00',
                'jam_selesai' => '09:30:00',
                'approval' => '1',
                'rejection_reason' => null,
            ],

        ];
        foreach ($jadwalKuliah as $jadwal) {
            JadwalKuliah::create($jadwal);
        }

        $userData = [
            [
                'name' => 'Pak Jon',
                'email' => 'akademik@akademik.com',
                'role' => 'akademik',
                'password' => bcrypt('123')
            ],
            [
                'name' => 'Alexander Vinsen',
                'email' => 'elvinsen@students.com',
                'role' => 'mahasiswa',
                'password' => bcrypt('123'),
                'nim' => 240601221001,
            ],
            [
                'name' => 'Chintya Kirana',
                'email' => 'kirana@students.com',
                'role' => 'mahasiswa',
                'password' => bcrypt('123'),
                'nim' => 240601221002,
            ],
            [
                'name' => 'Susi Susiastuti S.Kom, M.Kom',
                'email' => 'susi@lecturer.com',
                'role' => 'dosen',
                'password' => bcrypt('123'),
                'nip' => 12231305,
            ],
            [
                'name' => 'Dr. Antoni S.Kom, M. Si, P.Hd',
                'email' => 'antoni@lecturer.com',
                'role' => 'dekan',
                'password' => bcrypt('123'),
                'nip' => 12231201,
            ],
            [
                'name' => 'Budi Yono S.Kom, M.T',
                'email' => 'budi@lecturer.com',
                'role' => 'kaprodi',
                'password' => bcrypt('123'),
                'nip' => 12231202,
            ]
        ];
        foreach ($userData as $key => $val) {
            User::create($val);
        }
    }
}
