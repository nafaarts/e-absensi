@extends('layouts.app')

@section('content')
    <div class="row mb-2">
        <div class="col-md-3 pb-2">
            <div class="card rounded-0 p-3">
                <small class="text-muted">Total User</small>
                <h2 class="m-0">{{ $totalUser }}</h2>
            </div>
        </div>
        <div class="col-md-3 pb-2">
            <div class="card rounded-0 p-3">
                <small class="text-muted">Total Hadir (hari ini)</small>
                <h2 class="m-0">{{ $totalHadir }}</h2>
            </div>
        </div>
        <div class="col-md-3 pb-2">
            <div class="card rounded-0 p-3">
                <small class="text-muted">Total Tidak Hadir (hari ini)</small>
                <h2 class="m-0">{{ $totalTidakHadir }}</h2>
            </div>
        </div>
        <div class="col-md-3 pb-2">
            <div class="card rounded-0 p-3">
                <small class="text-muted">Total Izin</small>
                <h2 class="m-0">{{ $totalIzin }}</h2>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th scope="col">Waktu</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Jabatan</th>
                    <th scope="col">Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logAktivitas as $item)
                    <tr>
                        <td>{{ $item->created_at }}</td>
                        <th scope="row">{{ $item->user->nama }}</th>
                        <td>{{ $item->user->jabatan }}</td>
                        <td>{{ $item->deskripsi }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">
                            Tidak ada data.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
