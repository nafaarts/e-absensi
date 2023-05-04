@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="m-0">Edit Jadwal</h5>
    </div>
    <hr>
    <form action="{{ route('jadwal.update', $jadwal) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label for="hari">Hari</label>
            <input type="text" class="form-control" id="hari" disabled value="{{ str()->upper($jadwal->hari) }}">
        </div>

        <div class="form-group mb-3">
            <label for="jam_masuk">Jam Masuk</label>
            <input type="time" class="form-control" id="jam_masuk" name="jam_masuk" placeholder="Masukan jam masuk"
                value="{{ old('jam_masuk', $jadwal->jam_masuk) }}">
            @error('jam_masuk')
                <small class="text-danger">
                    {{ $message }}
                </small>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="jam_keluar">Jam Keluar</label>
            <input type="time" class="form-control" id="jam_keluar" name="jam_keluar" placeholder="Masukan jam keluar"
                value="{{ old('jam_keluar', $jadwal->jam_keluar) }}">
            @error('jam_keluar')
                <small class="text-danger">
                    {{ $message }}
                </small>
            @enderror
        </div>

        <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
@endsection
