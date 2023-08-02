<?php

namespace App\Http\Controllers\Admin;

use App\Models\Absensi;
use App\Models\Perizinan;
use App\Http\Controllers\Controller;

class PerizinanController extends Controller
{
    public function index()
    {
        // ambil data perizinan pada hari ini di database dan sort dari yang paling terbaru.
        $perizinan = Perizinan::whereDate('created_at', now()->today())->latest()->paginate();

        // tampilkan halaman index yang ada di folder resources/views/admin/perizinan/index.blade.php
        // dan kirimkan data perizinan ke halaman melalui compact dibawah.
        return view('admin.perizinan.index', compact('perizinan'));
    }


    public function show(Perizinan $perizinan)
    {
        // jika status dilihat masih false
        if (!$perizinan->di_lihat) {
            // update status di_lihat pada perizinan menjadi true
            $perizinan->update([
                'di_lihat' => true
            ]);
        }

        // tampilkan halaman detail yang ada di folder resources/views/admin/perizinan/detail.blade.php
        // dan kirimkan data perizinan yang ada di parameter ke halaman melalui compact dibawah.
        return view('admin.perizinan.detail', compact('perizinan'));
    }


    public function update(Perizinan $perizinan)
    {
        // ubah status perizinan terkait di database
        $perizinan->update([
            'status_izin' => 1
        ]);

        if ($perizinan->jumlah_hari > 1) {
            $data = [];
            for ($i = 1; $i <= $perizinan->jumlah_hari - 1; $i++) {
                $absensi = Absensi::create([
                    'user_id' => $perizinan->absensi->user->id,
                    "created_at" => now()->addDay($i)->format('Y-m-d H:i:s'),
                    "updated_at" => now()->addDay($i)->format('Y-m-d H:i:s'),
                ]);

                Perizinan::create([
                    "absensi_id" => $absensi->id,
                    "surat_izin" => $perizinan->surat_izin,
                    "kategori_izin" => $perizinan->kategori_izin,
                    "alasan_izin" => $perizinan->alasan_izin,
                    "jumlah_hari" => $perizinan->jumlah_hari,
                    "status_izin" => 1,
                    "di_lihat" => 1,
                    "created_at" => now()->addDay($i)->format('Y-m-d H:i:s'),
                    "updated_at" => now()->addDay($i)->format('Y-m-d H:i:s'),
                ]);
            }
        }

        // kembalikan ke halaman index beserta pesan berhasil
        return redirect()->route('perizinan.index')->with('berhasil', 'Status perizinan berhasil diubah.');
    }
}
