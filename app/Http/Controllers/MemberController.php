<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = User::where('role', '!=', 'admin');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('kecamatan_domisili', 'like', "%{$search}%");
            });
        }

        $totalMembers = User::where('role', '!=', 'admin')->count();
        $activeMembers = User::where('role', '!=', 'admin')->where(function ($q) {
            $q->whereNull('expired_at')->orWhere('expired_at', '>=', now());
        })->count();
        $expiredMembers = User::where('role', '!=', 'admin')->where('expired_at', '<', now())->count();

        $page = max(1, (int) $request->input('page', 1));
        $filteredTotal = (clone $query)->count();
        $maxPage = max(1, (int) ceil($filteredTotal / 15));
        if ($page > $maxPage) {
            $page = 1;
        }

        $members = $query->orderBy('name', 'asc')->paginate(15, ['*'], 'page', $page);
        $members->appends($request->all());

        return view('admin.members', compact('members', 'search', 'totalMembers', 'activeMembers', 'expiredMembers'));
    }

    public function export(Request $request)
    {
        $type = $request->input('type');

        $members = User::where('role', '!=', 'admin')
            ->orderBy('name', 'asc')
            ->get();

        if ($type === 'excel') {
            return $this->exportExcel($members);
        } elseif ($type === 'pdf') {
            return $this->exportPdf($members);
        }

        return back()->with('error', 'Tipe ekspor tidak valid.');
    }

    private function exportExcel($members)
    {
        $fileName = "Rekap_Data_Anggota_Terdaftar.xlsx";
        $tempPath = tempnam(sys_get_temp_dir(), 'export_member_') . '.xlsx';

        $writer = new Writer();
        $writer->openToFile($tempPath);

        // Header Style (Bold)
        $headerStyle = (new Style())->withFontBold(true);

        // Judul Laporan
        $writer->addRow(Row::fromValuesWithStyle(["REKAP DATA ANGGOTA TERDAFTAR PERPUSTAKAAN"], $headerStyle));
        $writer->addRow(Row::fromValues([]));

        // Header Kolom
        $headers = ['NO', 'NIM', 'NAMA ANGGOTA', 'GENDER', 'UMUR', 'WILAYAH', 'STATUS'];
        $writer->addRow(Row::fromValuesWithStyle($headers, $headerStyle));

        // Baris Data
        if ($members->isEmpty()) {
            $writer->addRow(Row::fromValues(['-', '-', 'Tidak ada data anggota terdaftar.', '-', '-', '-', '-']));
        } else {
            foreach ($members as $index => $member) {
                $age = $member->tanggal_lahir ? Carbon::parse($member->tanggal_lahir)->age . ' Thn' : '-';
                $gender = $member->gender === 'L' ? 'Laki-laki' : ($member->gender === 'P' ? 'Perempuan' : '-');
                $statusKartu = '-';
                if ($member->expired_at) {
                    $exp = Carbon::parse($member->expired_at);
                    $statusKartu = $exp->isPast() ? 'Kadaluarsa (exp: ' . $exp->format('d/m/Y') . ')' : 'Aktif (s.d. ' . $exp->format('d/m/Y') . ')';
                }

                $writer->addRow(Row::fromValues([
                    $index + 1,
                    $member->nim ?? '-',
                    $member->name,
                    $gender,
                    $age,
                    $member->kecamatan_domisili ?? '-',
                    $statusKartu,
                ]));
            }
        }

        $writer->close();

        return response()->download($tempPath, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    private function exportPdf($members)
    {
        $fileName = "Rekap_Data_Anggota_Terdaftar.pdf";

        $pdf = Pdf::loadView('admin.exports.members_pdf', compact('members'))
            ->setPaper('a4', 'portrait');

        return $pdf->download($fileName);
    }
}
