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
        $tanggal = json_decode($perizinan->kustom_tanggal, true);

        $jumlahHari = 1;
        if ($tanggal['sampai']) {
            $jumlahHari = now()->parse($tanggal['dari'])->subDay(1)->diffInDays($tanggal['sampai']);
        }

        for ($i = 0; $i < $jumlahHari; $i++) {
            $currentTanggal = now()->parse($tanggal['dari'])->addDay($i);

            if ($currentTanggal->dayOfWeek != 0) {
                // cek absensi pada hari terkait
                $absensi = Absensi::whereDate('created_at', $currentTanggal)->first();
                if (!$absensi) {
                    $absensi = Absensi::create([
                        'user_id' => $perizinan->absensi->user->id,
                        "created_at" => $currentTanggal->format('Y-m-d H:i:s'),
                        "updated_at" => $currentTanggal->format('Y-m-d H:i:s'),
                    ]);
                }

                Perizinan::create([
                    "absensi_id" => $absensi->id,
                    "surat_izin" => $perizinan->surat_izin,
                    "kategori_izin" => $perizinan->kategori_izin,
                    "alasan_izin" => $perizinan->alasan_izin,
                    "jumlah_hari" => $perizinan->jumlah_hari,
                    'kustom_tanggal' => json_encode(['dari' => $currentTanggal->format('Y-m-d'), 'sampai' => null]),
                    "status_izin" => 1,
                    "di_lihat" => 1,
                    "created_at" => $currentTanggal->format('Y-m-d H:i:s'),
                    "updated_at" => $currentTanggal->format('Y-m-d H:i:s'),
                ]);
            }
        }

        $perizinan->delete();

        // kembalikan ke halaman index beserta pesan berhasil
        return redirect()->route('perizinan.index')->with('berhasil', 'Status perizinan berhasil diubah.');
    }
}
