<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IRS - {{ $mahasiswa->nim }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            margin: 1.5cm;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            font-size: 14px;
            margin: 0;
            padding: 0;
        }

        .header h3 {
            font-size: 12px;
            margin: 5px 0 0 0;
            padding: 0;
        }

        .student-info {
            margin-bottom: 15px;
        }

        .student-info table {
            font-size: 10px;
            margin-bottom: 10px;
        }

        .student-info td {
            padding: 2px 5px;
            border: none;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9px;
        }

        .main-table th {
            background-color: #f4f4f4;
            padding: 4px;
            font-size: 9px;
            text-align: center;
            vertical-align: middle;
            border: 0.5px solid #000;
        }

        .main-table td {
            padding: 3px 4px;
            border: 0.5px solid #000;
            vertical-align: top;
        }

        .main-table th:nth-child(1),
        .main-table td:nth-child(1) {
            width: 25px;
            text-align: center;
        }

        .main-table th:nth-child(2),
        .main-table td:nth-child(2) {
            width: 60px;
        }

        .main-table th:nth-child(4),
        .main-table td:nth-child(4) {
            width: 30px;
            text-align: center;
        }

        .main-table th:nth-child(5),
        .main-table td:nth-child(5) {
            width: 40px;
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
        }

        .footer p {
            margin: 0;
            padding: 0;
            line-height: 1.2;
        }

        .signature-space {
            margin: 15px 0;
            height: 40px;
        }

        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .print-date {
            font-size: 8px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>ISIAN RENCANA STUDI (IRS)</h2>
        <h3>{{ $irs->tahun_ajaran }} - Semester {{ $irs->semester }} ({{ $semester_text }})</h3>
    </div>

    <div class="student-info">
        <table width="100%">
            <tr>
                <td width="100">NIM</td>
                <td width="10">:</td>
                <td>{{ $mahasiswa->nim }}</td>
                <td width="100">Program Studi</td>
                <td width="10">:</td>
                <td>{{ $mahasiswa->prodi->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td>{{ $mahasiswa->name }}</td>
                <td>Total SKS</td>
                <td>:</td>
                <td>{{ $irs->total_sks }}</td>
            </tr>
        </table>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode MK</th>
                <th>Mata Kuliah</th>
                <th>SKS</th>
                <th>Kelas</th>
                <th>Jadwal</th>
                <th>Dosen Pengampu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($irs->jadwalKuliah as $index => $jadwal)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $jadwal->kodemk }}</td>
                <td>{{ $jadwal->matakuliah->nama_mk }}</td>
                <td>{{ $jadwal->matakuliah->sks }}</td>
                <td>{{ $jadwal->class_group }}</td>
                <td>{{ $jadwal->hari }}, {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                <td>{{ $jadwal->pembimbingakd->name ?? '-' }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3" align="right">Total SKS:</td>
                <td colspan="4">{{ $irs->total_sks }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p class="print-date">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>

        <div style="margin-top: 50px;">
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="width: 50%; text-align: center; border: none;">
                        Mahasiswa,<br><br><br><br>
                        <u>{{ $mahasiswa->name }}</u><br>
                        NIM: {{ $mahasiswa->nim }}
                    </td>
                    <td style="width: 50%; text-align: center; border: none;">
                        Dosen Wali,<br><br><br><br>
                        <u>{{ $mahasiswa->pembimbingAkd->name }}</u><br>
                        NIP:{{ $mahasiswa->dosen_id }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>