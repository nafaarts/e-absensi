<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'nama' => 'Administrator',
            'nip' => '000000000',
            'jabatan' => 'Operator',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'hak_akses' => 'admin'
        ]);

        \App\Models\Jadwal::insert([
            [
                'hari' => 'senin',
                'jam_masuk' => '07:30',
                'jam_keluar' => '14:30'
            ],
            [
                'hari' => 'selasa',
                'jam_masuk' => '07:30',
                'jam_keluar' => '14:30'
            ],
            [
                'hari' => 'rabu',
                'jam_masuk' => '07:30',
                'jam_keluar' => '14:30'
            ],
            [
                'hari' => 'kamis',
                'jam_masuk' => '07:30',
                'jam_keluar' => '14:30'
            ],
            [
                'hari' => 'jumat',
                'jam_masuk' => '07:30',
                'jam_keluar' => '11:30'
            ],
            [
                'hari' => 'sabtu',
                'jam_masuk' => '07:30',
                'jam_keluar' => '14:30'
            ],
        ]);
    }
}
