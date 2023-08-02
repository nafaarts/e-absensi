<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RekapController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $month = date('m');
        $year = date('Y');

        $periode = explode('-', request('periode'));
        if (request()->has('periode') && count($periode) == 2) {
            $month = $periode[1] < 12 ? $periode[1] : date('m');
            $year = $periode[0] < date('Y') ? $periode[0] : date('Y');
        }

        $totalDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $firstDateOfMonth = now()->create($year, $month);

        // buat label
        $labels[] = 'Nama';
        for ($o = 1; $o <= $totalDays; $o++) {
            $labels[] = now()->create($year, $month, $o)->format('d-M');
        }

        // inisialisasi data absensi
        $usersAttendances['labels'] = $labels;
        $usersAttendances['periode'] = $firstDateOfMonth->format('d-m-Y') . ' - ' . $firstDateOfMonth->addDays($totalDays - 1)->format('d-m-Y');
        $usersAttendances['data'] = User::when(request('kategori'), function ($query) {
            if (request('kategori') != 'all') {
                return $query->where('hak_akses', request('kategori'));
            }
        }, function ($query) {
            return $query->whereNot('hak_akses', 'admin');
        })
            ->when(request('cari') != '', function ($query) {
                return $query->where('nama', 'like', '%' . request('cari') . '%');
            })
            ->latest()
            ->paginate()
            ->withQueryString()
            ->through(function ($data) use ($totalDays, $year, $month) {
                $result['nama'] = $data->nama;
                $result['userId'] = $data->id;

                for ($o = 1; $o <= $totalDays; $o++) {
                    $tanggal = now()->create($year, $month, $o)->format('Y-m-d');

                    $absensi = $data->absensi()->whereDate('created_at', $tanggal)->first();
                    $log['masuk'] = $absensi?->logMasuk()->exists();
                    $log['keluar'] = $absensi?->logKeluar()->exists();
                    $log['izin'] = $absensi?->izinStatus();
                    $log['terlambat'] = $absensi?->menit_terlambat > 0;
                    $log['izinKategori'] = $absensi?->izinKategori();

                    $result['absensi'][] = (object) $log;
                }

                return (object) $result;
            });

        return view('admin.rekap.index', compact('usersAttendances', 'year', 'month'));
    }
}
