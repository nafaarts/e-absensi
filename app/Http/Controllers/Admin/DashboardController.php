<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\LogAktivitas;
use App\Models\Perizinan;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // cek jika user bukan admin. jika kondisi salah maka lanjutkan ke baris 28
        if (auth()->user()->hak_akses != 'admin') {
            // jika iya, maka redirect ke absensi.
            return redirect()->route('absensi');
        }

        // ambil data log aktifitas di database
        $logAktivitas = LogAktivitas::latest()->limit(10)->get();

        // ambil jumlah user yang bukan admin di database
        $totalUser = User::where('hak_akses', '!=', 'admin')->count();

        // ambil jumlah user yang izin pada hari ini di database
        $totalIzin = Perizinan::whereDate('created_at', now()->today())->count();

        // ambil jumlah user yang hadir pada hari ini di database
        $totalHadir = Absensi::whereDate('created_at', now()->today())->count() - $totalIzin;

        // hitung total yang tidak hadir
        $totalTidakHadir = $totalUser - $totalHadir;

        // tampilkan halaman dashboard yang ada di folder resources/views/admin/dashboard.blade.php
        // kirimkan data log aktifitas yang ada di variabel $logAktivitas.
        // kirimkan data total user yang ada di variabel $totalUser.
        // kirimkan data total user yang hadir di variabel $totalHadir.
        // kirimkan data total user yang tidak hadir di variabel $totalTidakHadir.
        // dan kirimkan data total perizinan yang ada di variabel $totalIzin.
        return view('admin.dashboard', compact('logAktivitas', 'totalUser', 'totalIzin', 'totalHadir', 'totalTidakHadir'));
    }
}