<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    // referensi hari (terjemah ke bahasa indonesia)
    private $hari = [
        "Sunday" => "Minggu",
        "Monday" => "Senin",
        "Tuesday" => "Selasa",
        "Wednesday" => "Rabu",
        "Thursday" => "Kamis",
        "Friday" => "Jumat",
        "Saturday" => "Sabtu",
    ];

    public function absensi()
    {
        // format jam 'H:i:s'
        // ambil jadwal jam masuk dan keluar untuk hari ini di database
        $jadwalAbsen = Jadwal::where('hari', strtolower($this->hari[now()->format('l')]))->first();

        // ambil data absensi hari ini (jika ada)
        $todayAbsen = Absensi::where('user_id', auth()->id())->whereDate('created_at', now()->today())->first();

        // buat tipe absen, jika data absen hari ini ada maka tipe absen adalah 'keluar' dan jika tidak maka 'masuk'.
        $tipeAbsen = $todayAbsen?->logMasuk ? "KELUAR" : "MASUK";

        // tampilkan halaman absen yang ada di folder resources/views/absensi.blade.php dan kirimkan data todayAbsen, jadwalAbsen, tipeAbsen
        return view('absensi', compact('todayAbsen', 'jadwalAbsen', 'tipeAbsen'));
    }

    public function store(Request $request)
    {
        // validasi data yang diinput.
        $request->validate([
            'photo' => 'required|image',
            'latitude' => 'required',
            'longitude' => 'required'
        ]);

        // ambil data jadwal absensi hari ini (jam_masuk dan jam keluar)
        $jadwalAbsen = Jadwal::where('hari', strtolower($this->hari[now()->format('l')]))->first();

        // ambil data absen hari ini (jika ada)
        $todayAbsen = Absensi::where('user_id', auth()->id())->whereDate('created_at', now()->today())->first();

        // lakukan pemeriksaan jika sudah ada data absensi
        if (!$todayAbsen?->logMasuk()) {
            // buat absensi masuk

            // cek apakah jam sudah sesuai / lebih dari jadwal keluar
            if (now()->diffInMinutes($jadwalAbsen?->jam_keluar, false) < 0) {
                // jika iya, tampilkan pesan gagal
                return back()->with('gagal', 'Absen masuk hanya dapat dilakukan sebelum jam keluar.');
            }

            // dispensasi jam masuk selama 10 menit
            $jamMasuk = now()->parse($jadwalAbsen?->jam_masuk)->addMinutes(10);

            // hitung jumlah menit terlambat
            $telat = now()->diffInMinutes($jamMasuk, false); // kalo mines berarti telat

            // buat data absensi dan masukan ke database
            $absensi = Absensi::create([
                'user_id' => auth()->id(),
                'menit_terlambat' => $telat < 0 ? $telat * -1 : 0,
            ]);

            // simpan foto ke storage / server
            $request->file('photo')->store('public/img/absensi/');

            // tambah data log masuk ke database
            $absensi->logMasuk()->create([
                'tipe' => 'MASUK',
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'foto' => $request->file('photo')->hashName()
            ]);

            // tambah log aktifitas (deskripsi) ke database
            LogAktivitas::create([
                'user_id' => auth()->id(),
                'deskripsi' => 'Melakukan Absensi Masuk pada ' . now()->format('H:i') . ' WIB ' . ($telat < 0 ? ' (Terlambat ' . ($telat * -1) . ' Menit)' : '')
            ]);

            // kembalikan ke halaman sebelumnya dengan pesan berhasil.
            return back()->with('berhasil', 'Anda telah melakukan absensi masuk.');
        } else {
            // buat absensi keluar

            // cek apakah jam sudah sesuai / lebih dari jadwal keluar
            if (now()->diffInMinutes($jadwalAbsen?->jam_keluar, false) > 0) {
                // jika iya, kembalikan ke halaman sebelumnya dengan pesan gagal
                return back()->with('gagal', 'Absen keluar hanya dapat dilakukan pada dan setelah pukul ' . $jadwalAbsen?->jam_keluar . ' WIB');
            }

            // hitung jumlah menit lembur
            $lembur = now()->diffInMinutes($jadwalAbsen?->jam_keluar, false); // kalo mines berarti lembur

            // ubah data lembur di database absensi hari ini
            $todayAbsen->update([
                'menit_lembur' => $lembur < 0 ? $lembur * -1 : 0,
            ]);

            // simpan foto ke storage / server
            $request->file('photo')->store('public/img/absensi/');

            // buat log absensi keluar dan simpan ke database
            $todayAbsen->logKeluar()->create([
                'tipe' => 'KELUAR',
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'foto' => $request->file('photo')->hashName()
            ]);

            // buat deskripsi log aktifitas dan simpan ke database.
            LogAktivitas::create([
                'user_id' => auth()->id(),
                'deskripsi' => 'Melakukan Absensi Keluar pada ' . now()->format('H:i') . ' WIB'
            ]);

            // kembalikan ke halaman sebelumnya dengan pesan berhasil
            return back()->with('berhasil', 'Anda telah melakukan absensi keluar.');
        }
    }
}
