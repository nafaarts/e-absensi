@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="m-0">Tambah User</h5>
    </div>
    <hr>
    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
            <label for="nama">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukan nama lengkap"
                value="{{ old('nama') }}">
            @error('nama')
                <small class="text-danger">
                    {{ $message }}
                </small>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="nip">NIP</label>
            <input type="text" class="form-control" id="nip" name="nip" placeholder="Masukan NIP"
                value="{{ old('nip') }}">
            @error('nip')
                <small class="text-danger">
                    {{ $message }}
                </small>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="jabatan">Jabatan</label>
            <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="Masukan jabatan"
                value="{{ old('jabatan') }}">
            @error('jabatan')
                <small class="text-danger">
                    {{ $message }}
                </small>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Masukan email"
                value="{{ old('email') }}">
            @error('email')
                <small class="text-danger">
                    {{ $message }}
                </small>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="hak_akses">Hak Akses</label>
            <select name="hak_akses" id="hak_akses" class="form-select">
                <option value="" disabled>Pilih hak akses</option>
                @foreach (['guru', 'pegawai'] as $item)
                    <option value="{{ $item }}" @selected(old('hak_akses') == $item)>{{ $item }}</option>
                @endforeach
            </select>
            @error('hak_akses')
                <small class="text-danger">
                    {{ $message }}
                </small>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Masukan password"
                value="{{ old('password') }}">
            @error('password')
                <small class="text-danger">
                    {{ $message }}
                </small>
            @enderror
        </div>

        <div class="form-group mb-4">
            <label for="password_confirmation">Ulangi Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                placeholder="Ulangi password" value="{{ old('password_confirmation') }}">
        </div>

        <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
@endsection
