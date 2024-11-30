<!DOCTYPE html>
<html>

<head>
    <title>IRS</title>
    <style>
        /* Tambahkan gaya CSS untuk menyesuaikan tampilan tabel */
    </style>
</head>

<body>
    <h1>Isian Rencana Studi (IRS)</h1>
    <table>
        <thead>
            <th>Nama Mata Kuliah</th>
            <th>Kode MK</th>
            <th>SKS</th>
            <th>Semester</th>
            <th>Dosen Pengampu</th>
        </thead>
        <tbody>
            @foreach ($jadwal as $item)
            <tr>
                <td>{{ $item->mataKuliah->nama_mk }}</td>
                <td>{{ $item->mataKuliah->kodemk }}</td>
                <td>{{ $item->mataKuliah->sks }}</td>
                <td>{{ $item->plot_semester }}</td>
                <td>{{ $item->pembimbingakd->nama }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>