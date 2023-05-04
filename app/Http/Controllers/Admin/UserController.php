<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // ambil semua data user didatabase.
        $users = User::where('hak_akses', '!=', 'admin')->paginate(15);

        // tampilkan halaman index user yang ada di folder resources/views/admin/users/index.blade.php
        // dan kirimkan semua data users divariable $users.
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        // tampilkan halaman index create yang ada di folder resources/views/admin/users/create.blade.php
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        // validasi data yang diinput.
        $validasi = $request->validate([
            'nama' => 'required',
            'nip' => 'required',
            'jabatan' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'hak_akses' => 'required'
        ]);

        // encrypsi password agar lebih aman.
        $validasi['password'] = bcrypt($validasi['password']);

        // masukan data ke dalam database.
        User::create($validasi);

        // kembalikan halaman ke index user dan kirimkan pesan berhasil
        return redirect()->route('users.index')->with('berhasil', 'Data user berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
       // tampilkan halaman index edit yang ada di folder resources/views/admin/users/edit.blade.php
       // dan kirimkan data user yang ada diparameter.
       return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // validasi data yang input
        $request->validate([
            'nama' => 'required',
            'nip' => 'required',
            'jabatan' => 'required',
            'email' => 'required|email',
            'password' => 'nullable|min:8|confirmed',
            'hak_akses' => 'required'
        ]);

        // replace data di database dengan yang di input.
        $user->nama = $request->nama;
        $user->nip = $request->nip;
        $user->jabatan = $request->jabatan;
        $user->email = $request->email;
        $user->hak_akses = $request->hak_akses;

        // cek jika user masukan password (ganti password)
        if ($request->password) {
            // replace password dengan password yang di encrypsi
            $user->password = bcrypt($request->password);
        }

        // simpan data yang baru di database.
        $user->save();

        // kembalikan halaman ke index user dan kirimkan pesan berhasil
        return redirect()->route('users.index')->with('berhasil', 'Data user berhasil diubah.');
    }

    public function destroy(User $user)
    {
        // hapus data di database.
        $user->delete();

        // kembalikan halaman ke index user dan kirimkan pesan berhasil
        return redirect()->route('users.index')->with('berhasil', 'Data user berhasil dihapus.');
    }
}
