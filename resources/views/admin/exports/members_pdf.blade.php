<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Data Anggota Terdaftar</title>
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
        .badge-active {
            color: #059669;
            font-weight: bold;
        }
        .badge-expired {
            color: #dc2626;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REKAP DATA ANGGOTA TERDAFTAR</h1>
        <p>Sistem Informasi Perpustakaan & Kiosk Digital</p>
    </div>

    <div class="meta-info">
        <table>
            <tr>
                <td style="width: 20%;"><strong>Total Anggota Terdaftar</strong></td>
                <td style="width: 30%;">: {{ count($members) }} Orang</td>
                <td style="width: 25%; text-align: right;"><strong>Waktu Cetak</strong></td>
                <td style="width: 25%; text-align: right;">: {{ now()->translatedFormat('d F Y, H:i') }}</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 15%;">NIM</th>
                <th style="width: 25%;">Nama Anggota</th>
                <th style="width: 13%;">Gender</th>
                <th style="width: 10%;">Umur</th>
                <th style="width: 15%;">Wilayah</th>
                <th style="width: 17%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($members as $index => $member)
                @php
                    $age = $member->tanggal_lahir ? \Carbon\Carbon::parse($member->tanggal_lahir)->age . ' Thn' : '-';
                    $gender = $member->gender === 'L' ? 'Laki-laki' : ($member->gender === 'P' ? 'Perempuan' : '-');
                    $isExpired = $member->expired_at ? \Carbon\Carbon::parse($member->expired_at)->isPast() : null;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $member->nim ?? '-' }}</strong></td>
                    <td>{{ $member->name }}</td>
                    <td>{{ $gender }}</td>
                    <td>{{ $age }}</td>
                    <td>{{ $member->kecamatan_domisili ?? '-' }}</td>
                    <td>
                        @if($member->expired_at)
                            @if($isExpired)
                                <div style="color: #dc2626; font-weight: bold;">
                                    ● Kadaluarsa
                                </div>
                                <div style="font-size: 9px; color: #991b1b; margin-top: 1px;">
                                    exp: {{ \Carbon\Carbon::parse($member->expired_at)->format('d/m/Y') }}
                                </div>
                            @else
                                <div style="color: #059669; font-weight: bold;">
                                    ● Aktif
                                </div>
                                <div style="font-size: 9px; color: #065f46; margin-top: 1px;">
                                    s.d. {{ \Carbon\Carbon::parse($member->expired_at)->format('d/m/Y') }}
                                </div>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty-row">Tidak ada data anggota terdaftar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak secara otomatis oleh Sistem Admin pada {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
