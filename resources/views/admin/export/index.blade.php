@extends('layouts.app')

@section('content')
    <h5 class="py-1">Ekspor Absensi</h5>
    <hr>
    <form action="{{ route('export.absensi') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
            <label for="periode">Periode</label>
            <input type="month" class="form-control" id="periode" name="periode" placeholder="Masukan periode"
                value="{{ date('Y-m') }}">
        </div>

        <div class="form-group mb-3">
            <label for="kategori">Kategori</label>
            <select name="kategori" id="kategori" class="form-select">
                <option value="" disabled>Pilih kategori</option>
                <option value="SEMUA">SEMUA</option>
                <option value="GURU">GURU</option>
                <option value="PEGAWAI">PEGAWAI</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-file-earmark-excel"></i> Export
        </button>
    </form>
@endsection
