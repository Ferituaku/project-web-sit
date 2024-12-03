<!DOCTYPE html>
<html>

<head>
    <title>IRS - {{ $mahasiswa->nim }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
        }

        .info {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>ISIAN RENCANA STUDI (IRS)</h2>
        <h3>{{ $irs->tahun_ajaran }} - Semester {{ $semester_text }}</h3>
    </div>

    <div class="info">
        <p>NIM: {{ $mahasiswa->nim }}</p>
        <p>Nama: {{ $mahasiswa->nama }}</p>
        <p>Program Studi: {{ $mahasiswa->prodi }}</p>
        <p>Total SKS: {{ $irs->total_sks }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Kode MK</th>
                <th>Mata Kuliah</th>
                <th>SKS</th>
                <th>Kelas</th>
                <th>Jadwal</th>
                <th>Dosen Pengampu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($irs->jadwalKuliah as $jadwal)
            <tr>
                <td>{{ $jadwal->kodemk }}</td>
                <td>{{ $jadwal->matakuliah->nama_mk }}</td>
                <td>{{ $jadwal->matakuliah->sks }}</td>
                <td>{{ $jadwal->class_group }}</td>
                <td>{{ $jadwal->hari }}, {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                <td>{{ $jadwal->pembimbingakd->nama ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 50px;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 50%; text-align: center; border: none;">
                    Mahasiswa,<br><br><br><br>
                    {{ $mahasiswa->nama }}<br>
                    NIM: {{ $mahasiswa->nim }}
                </td>
                <td style="width: 50%; text-align: center; border: none;">
                    Dosen Wali,<br><br><br><br>
                    ______________________<br>
                    NIP:
                </td>
            </tr>
        </table>
    </div>
</body>

</html>