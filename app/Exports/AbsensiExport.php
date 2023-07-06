<?php

namespace App\Exports;

use App\Models\Absensi;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AbsensiExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    private string $year;
    private string $month;
    private string $category;

    public function __construct($year, $month, $category)
    {
        $this->year = $year;
        $this->month = $month;
        $this->category = $category;
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Kategori',
            'Jabatan',
            'Jumlah Hadir',
            'Jumlah Tidak Hadir',
            'Jumlah Izin',
            'Menit Terlambat',
            'Menit Lembur'
        ];
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $year = $this->year;
        $month = $this->month;

        // ambil data user.
        $users = User::whereNot('hak_akses', 'admin');
        if ($this->category != 'SEMUA') $users = $users->where('hak_akses', strtolower($this->category));
        $users = $users->orderBy('jabatan')->get();

        $totalDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $data = collect([]);
        foreach ($users as $user) {
            $userData['nama'] = $user->nama;
            $userData['kategori'] = $user->hak_akses;
            $userData['jabatan'] = $user->jabatan;

            $hadir = 0;
            $tidak_hadir = 0;
            $izin = 0;
            $terlambat = 0;
            $lembur = 0;

            for ($i = 0; $i < $totalDays; $i++) {
                $absensi = $user->absensi()->whereDate('created_at', "$year-$month-" . sprintf("%'.02d", $i + 1))->first();

                $terlambat += $absensi?->menit_terlambat ?? 0;
                $lembur += $absensi?->menit_lembur ?? 0;

                if ($absensi?->izin?->status_izin) {
                    $izin += 1;
                    continue;
                }

                if ($absensi?->logMasuk && $absensi?->logKeluar) {
                    $hadir += 1;
                } else {
                    $tidak_hadir += 1;
                }
            }

            $userData['jumlah_hadir'] = $hadir;
            $userData['jumlah_tidak_hadir'] = $tidak_hadir;
            $userData['jumlah_izin'] = $izin;

            $userData['menit_terlambat'] = $terlambat;
            $userData['menit_lembur'] = $lembur;

            $data->push($userData);
        }

        // return Absensi::all();
        return $data;
    }
}
