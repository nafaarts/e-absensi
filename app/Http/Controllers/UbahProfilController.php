<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UbahProfilController extends Controller
{
    public function __invoke(Request $request)
    {
        // validasi data yang input.
        $request->validate([
            'nama' => 'required',
            'nip' => 'required',
            'jabatan' => 'required',
            'email' => 'required|email',
            'password' => 'nullable|confirmed|min:8',
        ]);

        // cari data user berdasarkan yang login.
        $user = User::findOrFail(auth()->id());

        // replace data di database dengan yang di input.
        $user->nama = $request->nama;
        $user->nip = $request->nip;
        $user->jabatan = $request->jabatan;
        $user->email = $request->email;

        // cek jika user masukan password (ganti password)
        if ($request->password) {
            // replace password dengan password yang di encrypsi
            $user->password = bcrypt($request->password);
        }

        // simpan data yang baru di database.
        $user->save();

        // kembalikan halaman dengan pesan berhasil.
        return back()->with('berhasil', 'Data profil berhasil diubah.');
    }
}