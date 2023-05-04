<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    }
}