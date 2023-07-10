@extends('layouts.app')

@section('content')
    <h5>Profil</h5>
    <hr>
    @if (session('berhasil'))
        <div class="alert alert-success" role="alert">
            <strong>Berhasil!</strong> {{ session('berhasil') }}
        </div>
    @endif
    <form action="{{ route('ubah-profil') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="nama">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukan nama"
                value="{{ old('nama', auth()->user()->nama) }}">
            @error('nama')
                <small class="text-danger">
                    {{ $message }}
                </small>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="nip">NIP</label>
            <input type="text" class="form-control" id="nip" name="nip" placeholder="Masukan nip"
                value="{{ old('nip', auth()->user()->nip) }}">
            @error('nip')
                <small class="text-danger">
                    {{ $message }}
                </small>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Masukan email"
                value="{{ old('email', auth()->user()->email) }}">
            @error('email')
                <small class="text-danger">
                    {{ $message }}
                </small>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="jabatan">Profesi</label>
            <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="Masukan jabatan"
                value="{{ old('jabatan', auth()->user()->jabatan) }}">
            @error('jabatan')
                <small class="text-danger">
                    {{ $message }}
                </small>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="password">Ubah Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Masukan password">
            @error('password')
                <small class="text-danger">
                    {{ $message }}
                </small>
            @enderror
        </div>

        <div class="form-group mb-4">
            <label for="password_confirmation">Ulangi Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                placeholder="Ulangi password">
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
@endsection
