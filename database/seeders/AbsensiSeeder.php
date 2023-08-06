<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\LogAbsensi;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AbsensiSeeder extends Seeder
{
    /**
     * Create fake datetime.
     */
    private function fakeDate(String $format, String $year, String $month, int $date, String $type = null): string
    {
        switch ($type) {
            case 'IN':
                $hour = sprintf("%'.02d", rand(6, 8));
                $minute = sprintf("%'.02d", rand(0, 59));
                $time = "$hour:$minute";
                break;

            case 'OUT':
                $hour = sprintf("%'.02d", rand(14, 18));
                $minute = sprintf("%'.02d", rand(30, 59));
                $time = "$hour:$minute";
                break;

            default:
                $time = "00:00";
                break;
        }

        return date($format, strtotime("$year-$month-" . sprintf("%'.02d", $date) . " $time"));
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $month = "07";
        $year = "2023";

        $dayToHari = [
            'Mon' => 'senin',
            'Tue' => 'selasa',
            'Wed' => 'rabu',
            'Thu' => 'kamis',
            'Fri' => 'jumat',
            'Sat' => 'sabtu',
        ];

        if ($month == now()->month) {
            $totalDays = now()->day;
        } else {
            $totalDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        }

        $users = User::whereNot('hak_akses', 'admin')->orderBy('jabatan')->get();
        foreach ($users as $user) {
            for ($i = 0; $i < $totalDays; $i++) {
                if ($this->fakeDate('N', $year, $month, $i + 1) == 7) {
                    continue;
                }

                if (rand(0, 10) == 7) {
                    // echo "libur dulu </br>";
                    continue;
                }

                $day = $this->fakeDate('D', $year, $month, $i + 1);
                $schedule = Jadwal::where('hari', $dayToHari[$day])->first();

                // time in
                $timeIn = $this->fakeDate('Y-m-d H:i:s', $year, $month, $i + 1, 'IN');
                $now = now()->setTimeFromTimeString($timeIn);
                $minutesLate = $now->format('H:i') > $schedule->jam_masuk ?
                    $now->diffInMinutes("$year-$month-" . sprintf("%'.02d", $i + 1) . " " . $schedule->jam_masuk) : 0;

                // time out
                $timeOut = $this->fakeDate('Y-m-d H:i:s', $year, $month, $i + 1, 'OUT');
                $now = now()->setTimeFromTimeString($timeOut);
                $minutesOvertime = $now->format('H:i') > $schedule->jam_keluar ?
                    $now->diffInMinutes("$year-$month-" . sprintf("%'.02d", $i + 1) . " " . $schedule->jam_keluar) : 0;

                $absensi = Absensi::create([
                    'user_id' => $user->id,
                    'menit_terlambat' => $minutesLate,
                    'menit_lembur' => $minutesOvertime,
                    'created_at' => $timeIn,
                    'updated_at' => $timeOut,
                ]);

                LogAbsensi::create([
                    'absensi_id' => $absensi->id,
                    'tipe' => 'MASUK',
                    'latitude' => 500,
                    'longitude' => 500,
                    'created_at' => $timeIn,
                    'updated_at' => $timeIn,
                ]);

                LogAbsensi::create([
                    'absensi_id' => $absensi->id,
                    'tipe' => 'KELUAR',
                    'latitude' => 500,
                    'longitude' => 500,
                    'created_at' => $timeOut,
                    'updated_at' => $timeOut,
                ]);
            }
        }
    }
}
