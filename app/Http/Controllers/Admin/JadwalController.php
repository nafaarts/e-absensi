<?php

namespace App\Http\Controllers\Admin;

use App\Models\Jadwal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JadwalController extends Controller
{
    public function index()
    {
        // ambil semua data jadwal di database.
        $jadwal = Jadwal::all();

        // buka halaman index jadwal yang ada di folder resources/views/admin/jadwal/index.blade.php
        // dan kirimkan data jadwal (compact dibawah) dari variable jadwal.
        return view('admin.jadwal.index', compact('jadwal'));
    }

    public function edit(Jadwal $jadwal)
    {
        // buka halaman edit jadwal yang ada di folder resources/views/admin/jadwal/edit.blade.php
        // dan kirimkan data jadwal (compact dibawah) dari parameter.
        return view('admin.jadwal.edit', compact('jadwal'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        // validasi data yang diinput.
        $validasi = $request->validate([
            'jam_masuk' => 'required',
            'jam_keluar' => 'required'
        ]);

        // update data di database dengan data yang telah divalidasi.
        $jadwal->update($validasi);

        // kembalikan halaman ke halaman index jadwal (list jadwal), disertakan pesan berhasil.
        return redirect()->route('jadwal.index')->with('berhasil', 'Jadwal berhasil diubah!');
    }
}