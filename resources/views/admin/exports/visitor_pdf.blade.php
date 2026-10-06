<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Log Kunjungan - {{ $monthName }} {{ $year }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #1e293b;
            margin: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .header h1 {
            font-size: 16px;
            text-transform: uppercase;
            margin: 0 0 4px 0;
            color: #0f172a;
        }
        .header p {
            margin: 2px 0;
            color: #475569;
            font-size: 11px;
        }
        .meta-info {
            margin-bottom: 14px;
            width: 100%;
        }
        .meta-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .meta-info td {
            font-size: 11px;
            padding: 2px 0;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        .data-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            padding: 8px 6px;
            border: 1px solid #0f172a;
            text-align: left;
        }
        .data-table td {
            padding: 7px 6px;
            border: 1px solid #cbd5e1;
            font-size: 10px;
        }
        .data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-center {
            text-align: center;
        }
        .empty-row {
            text-align: center;
            color: #94a3b8;
            font-style: italic;
            padding: 20px !important;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 9px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN LOG KUNJUNGAN DETAIL</h1>
        <p>Sistem Kiosk & Telemetri Pengunjung</p>
    </div>

    <div class="meta-info">
        <table>
            <tr>
                <td style="width: 15%;"><strong>Periode</strong></td>
                <td style="width: 35%;">: {{ $monthName }} {{ $year }}</td>
                <td style="width: 25%; text-align: right;"><strong>Total Kunjungan</strong></td>
                <td style="width: 25%; text-align: right;">: {{ count($logs) }} Kunjungan</td>
            </tr>
            <tr>
                <td><strong>Waktu Cetak</strong></td>
                <td>: {{ now()->translatedFormat('d F Y, H:i:s') }}</td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 22%;">Waktu Masuk</th>
                <th style="width: 30%;">Nama Pengunjung</th>
                <th style="width: 15%;">Gender</th>
                <th style="width: 10%;">Umur</th>
                <th style="width: 18%;">Wilayah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $index => $log)
                @php
                    $age = $log->user && $log->user->tanggal_lahir ? \Carbon\Carbon::parse($log->user->tanggal_lahir)->age . ' Thn' : '-';
                    $gender = '-';
                    if ($log->user) {
                        $gender = $log->user->gender === 'L' ? 'Laki-laki' : ($log->user->gender === 'P' ? 'Perempuan' : '-');
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, H:i') }}</td>
                    <td><strong>{{ $log->user->name ?? 'Unknown / Guest' }}</strong></td>
                    <td>{{ $gender }}</td>
                    <td>{{ $age }}</td>
                    <td>{{ $log->user->kecamatan_domisili ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="empty-row">Tidak ada data kunjungan pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak secara otomatis oleh Sistem Admin pada {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
