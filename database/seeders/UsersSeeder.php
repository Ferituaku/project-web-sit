<?php

namespace Database\Seeders;

use App\Models\JadwalKuliah;
use App\Models\Matakuliah;
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
        $userData = [
            [
                'name' => 'Pak Jon',
                'email' => 'akademik@akademik.com',
                'role' => 'akademik',
                'password' => bcrypt('123')
            ],
            [
                'name' => 'El Vinsen',
                'email' => 'elvinsen@students.com',
                'role' => 'mahasiswa',
                'password' => bcrypt('123')
            ],
            [
                'name' => 'Susi Susiastuti S.Kom, M.Kom',
                'email' => 'susi@lecturer.com',
                'role' => 'dosen',
                'password' => bcrypt('123')
            ],
            [
                'name' => 'Dr. Antoni S.Kom, M. Si, P.Hd',
                'email' => 'antoni@lecturer.com',
                'role' => 'dekan',
                'password' => bcrypt('123')
            ],
            [
                'name' => 'Budi Yono S.Kom, M.T',
                'email' => 'budi@lecturer.com',
                'role' => 'kaprodi',
                'password' => bcrypt('123')
            ]
        ];
        foreach ($userData as $key => $val) {
            User::create($val);
        }

        $ruangKelas = [
            [
                'koderuang' => 'A102',
                'kapasitas' => 40,
            ],
            [
                'koderuang' => 'A202',
                'kapasitas' => 30,
            ],
            [
                'koderuang' => 'E101',
                'kapasitas' => 50,
            ],
            [
                'koderuang' => 'B101',
                'kapasitas' => 30,
            ],
        ];
        foreach ($ruangKelas as $key => $val) {
            RuangKelas::create($val);
        }


        $matakuliah = [
            [
                'kodemk' => 'SIT1101',
                'nama_mk' => 'Algoritma Pemrograman',
                'sks' => 4,
                'semester' => 2,
            ],
            [
                'kodemk' => 'SIT1102',
                'nama_mk' => 'Basis Data',
                'sks' => 3,
                'semester' => 3,
            ],
            [
                'kodemk' => 'SIT1103',
                'nama_mk' => 'Pengembangan Berbasis Platform',
                'sks' => 4,
                'semester' => 5,
            ],
            [
                'kodemk' => 'SIT1104',
                'nama_mk' => 'Struktur Data',
                'sks' => 3,
                'semester' => 2,
            ],
            [
                'kodemk' => 'SIT1105',
                'nama_mk' => 'Sistem Operasi',
                'sks' => 3,
                'semester' => 3,
            ],
            [
                'kodemk' => 'SIT1106',
                'nama_mk' => 'Jaringan Komputer',
                'sks' => 2,
                'semester' => 4,
            ],
            [
                'kodemk' => 'SIT1107',
                'nama_mk' => 'Sistem Cerdas',
                'sks' => 3,
                'semester' => 4,
            ],
            [
                'kodemk' => 'SIT1108',
                'nama_mk' => 'Kewirausahaan',
                'sks' => 2,
                'semester' => 6,
            ]
        ];

        foreach ($matakuliah as $mk) {
            Matakuliah::create($mk);
        }
    }
}
