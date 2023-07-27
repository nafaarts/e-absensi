<?php

namespace App\Http\Controllers;

use App\Models\Absensi;

class RiwayatController extends Controller
{
    public function riwayat()
    {
        // ambil filter periode bulan dan tanggal di url (jika ada)
        // kalau tidak ada tetapkan dengan bulan dn tahun sekarang.
        $filter = request('m') ?? date('m') . '-' . date('Y');

        // pecahkan parameter periode  dari filter.
        [$month, $year] = explode('-', $filter);

        // buat data hari agar dapat di conversi
        $hari = [
            "Sunday" => "Minggu",
            "Monday" => "Senin",
            "Tuesday" => "Selasa",
            "Wednesday" => "Rabu",
            "Thursday" => "Kamis",
            "Friday" => "Jumat",
            "Saturday" => "Sabtu",
        ];

        // hitung jumlah hari dalam bulan
        $jumlahHariDalamBulan = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        // ambil hari pertama didalam bulan
        $hariPertamaDalamBulan = now()->create($year, $month, 1, 0);

        // format periode agar sesuai dengan contoh "April 2023"
        $periode = $hariPertamaDalamBulan->format('F Y');

        // hitung selilih hari dalam minggu
        // misalnya hari pertama bulan ini hari apa dan nomor hari keberapa dalam minggu
        $selisih = array_search($hariPertamaDalamBulan->format('l'), now()->getDays());

        // ambil data absensi dari database dalam periode yang ditentukan dan dari user yang sedang login.
        $absensi = Absensi::where('user_id', auth()->user()->id)
            ->whereMonth('created_at', '=', $month)
            ->whereYear('created_at', '=', $year)
            ->get();

        // looping data sebanyak jumlah hari dalam bulan.
        for ($i = 0; $i < $jumlahHariDalamBulan; $i++) {
            // buat tanggal dari index di tambah 1
            $tanggal = $i + 1;

            // check apakah hari dari tanggal diatas adalah hari minggu
            $isMinggu = ($i + $selisih) % 7 == 0;

            // filter data absensi yang sudah di ambil dari database di atas berdasarkan tanggal (dari index)
            // dan ambil yang pertama
            $data = $absensi->filter(fn ($item) => explode(' ', $item->created_at)[0] == now()->create($year, $month, $i + 1)->format('Y-m-d'))->first();

            // simpan ke dalam sebuah array agar dapat di looping di tampilan
            $dataAbsensi[$i] = [
                'hari_minggu' => $isMinggu,
                'tanggal' => $tanggal,
                'hari_ini' => now()->create($year, $month, $i + 1)->isToday(),
                'data' => $data,
                'masuk' => $data?->logMasuk ? true : false,
                'telat' => $data?->menit_terlambat ?? 0,
                'keluar' => $data?->logKeluar ? true : false,
                'izin' => $data?->izin?->status_izin ?? false
            ];
        }

        // buat format periode selanjutnya dari periode sekarang
        $bulanSelanjutnya = now()->create($year, $month, 1)->addMonth()->format('m-Y');

        // buat format periode sebelumnya dari periode sekarang
        $bulanSebelumnya = now()->create($year, $month, 1)->subMonth()->format('m-Y');

        // tampilkan halaman riwayat yang ada di folder resources/views/riwayat.blade.php
        // dan lampirkan variable variable yang diperlukan ke dalam compact()
        return view('riwayat', compact('hari', 'periode', 'selisih', 'dataAbsensi', 'bulanSelanjutnya', 'bulanSebelumnya'));
    }

    public function detail(Absensi $absensi)
    {
        // tampilkan halaman detail yang ada di folder resources/views/riwayat-detail.blade.php
        // dan lampirkan variable absensi yang ada diparameter.
        return view('riwayat-detail', compact('absensi'));
    }
}
