<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\LogAktivitas;
use App\Models\Perizinan;
use Illuminate\Http\Request;

class PerizinanController extends Controller
{
    public function index()
    {
        // ambil semua data perizinan milik user yang sedang login.
        $perizinan = Perizinan::whereHas('absensi', function ($query) {
            return $query->where('user_id', auth()->id());
        })->latest()->paginate(15);

        // tampilkan halaman perizinan yang ada di folder resourcers/views/perizinan.blade.php
        // dengan melampirkan data perizinan yang telah diambil.
        return view('perizinan', compact('perizinan'));
    }

    public function store(Request $request)
    {
        // validasi data perizinan yang diinput
        $request->validate([
            'surat_izin' => 'required|mimes:jpg,jpeg,png,pdf',
            'alasan_izin' => 'required',
            'dari_tanggal' => 'required',
            'sampai_tanggal' => 'nullable',
            'kategori_izin' => 'required',
        ]);

        $jumlahHari = 1;
        if ($request->sampai_tanggal) {
            if ($request->dari_tanggal > $request->sampai_tanggal) {
                return back()->with('gagal', 'Jarak tanggal tidak valid.');
            }
            $jumlahHari = now()->parse($request->dari_tanggal)->diffInDays($request->sampai_tanggal);
        }

        $hariJson = json_encode(['dari' => $request->dari_tanggal, 'sampai' => $request->sampai_tanggal ?? null]);

        // ambil data absensi hari ini (jika ada)
        $absensiHariIni = Absensi::where('user_id', auth()->id())->whereDate('created_at', now()->today())->first();

        // lakukan pengecekan data absensi hari ini
        if (!$absensiHariIni) {
            // jika tidak ada, maka buat data absensi hari ini
            $absensiHariIni = Absensi::create([
                'user_id' => auth()->id()
            ]);
        }

        // upload surat izin ke storage / server
        $request->file('surat_izin')->store('public/perizinan/');

        // buat data perizinan dan masukan ke dalam database.
        Perizinan::create([
            'absensi_id' => $absensiHariIni->id,
            'jumlah_hari' => $jumlahHari,
            'kustom_tanggal' => $hariJson,
            'surat_izin' => $request->file('surat_izin')->hashName(),
            'alasan_izin' => $request->alasan_izin,
            'kategori_izin' => $request->kategori_izin,
        ]);

        // tambah log aktifitas (deskripsi) ke database
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'deskripsi' => 'Membuat Perizinan pada ' . now()->format('H:i') . ' WIB'
        ]);

        // kembalikan kehalaman sebelumnya dengan pesan berhasil.
        return back()->with('berhasil', 'Perizinan berhasil dibuat, menunggu persetujuan admin');
    }

    public function show(Perizinan $perizinan)
    {
        // tampilkan halaman detail perizinan yang berada di folder resources/views/perizinan-detail.blade.php
        // dan lampirkan data perizinan yang aada di parameter.
        return view('perizinan-detail', compact('perizinan'));
    }

    // public function destroy(Perizinan $perizinan)
    // {
    //     // hapus data perizinan yang ada di database.
    //     $perizinan->delete();

    //     // kembalikan ke halaman sebelumnya dengan pesan berhasil.
    //     return back()->with('berhasil', 'Perizinan berhasil dihapus!');
    // }
}
