<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Log Kunjungan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Log Kunjungan Detail</h1>
        <p>UPA Perpustakaan Politeknik Negeri Batam</p>
        <p><strong>Periode: {{ $monthName }} {{ $year }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="20%">Waktu Masuk</th>
                <th width="35%">Nama Pengunjung</th>
                <th width="10%" class="text-center">Gender</th>
                <th width="10%" class="text-center">Umur</th>
                <th width="20%">Wilayah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $index => $log)
                @php
                    $age = $log->user && $log->user->tanggal_lahir ? \Carbon\Carbon::parse($log->user->tanggal_lahir)->age . ' Thn' : '-';
                    $gender = '-';
                    if ($log->user) {
                        $gender = $log->user->gender === 'L' ? 'L' : ($log->user->gender === 'P' ? 'P' : '-');
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $log->created_at ? \Carbon\Carbon::parse($log->created_at)->format('d-m-Y H:i') : '-' }}</td>
                    <td>{{ $log->user->name ?? 'Unknown / Guest' }}</td>
                    <td class="text-center">{{ $gender }}</td>
                    <td class="text-center">{{ $age }}</td>
                    <td>{{ $log->user->kecamatan_domisili ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data kunjungan pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
