<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perizinan;

class PerizinanController extends Controller
{
    public function index()
    {
        // ambil data perizinan di database dan sort dari yang paling terbaru.
        $perizinan = Perizinan::latest()->paginate();

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
            'status_izin' => !$perizinan->status_izin
        ]);

        // kembalikan ke halaman index beserta pesan berhasil
        return redirect()->route('perizinan.index')->with('berhasil', 'Status perizinan berhasil diubah.');
    }
}
