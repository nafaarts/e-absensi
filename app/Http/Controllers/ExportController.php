<?php

namespace App\Http\Controllers;

use App\Exports\AbsensiExport;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\User;
use Illuminate\Http\Request;
use Excel;

class ExportController extends Controller
{
    public function index()
    {
        // tampilkan halaman export yang ada di folder resources/views/admin/export/index.blade.php
        return view('admin.export.index');
    }

    public function absensi(Request $request)
    {
        // pecahkan data tahun dan bulan yang diinput.
        [$year, $month] = explode('-', $request->periode);
        $kategori = $request->kategori;

        // export laporan absensi
        return Excel::download(new AbsensiExport($year, $month, $kategori), 'rekapitulasi-absensi.xlsx');
    }
}
