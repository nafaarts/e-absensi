@extends('layouts.app')

@section('content')
    <h5 class="py-1">Data Perizinan</h5>
    <hr>
    @if (session('berhasil'))
        <div class="alert alert-success" role="alert">
            <strong>Berhasil!</strong> {{ session('berhasil') }}
        </div>
    @endif
    <small class="d-block mb-2">Menampilkan data perizinan pada hari ini.</small>
    <div class="table-responsive">
        <table class="table table-bordered table-striped-columns">
            <thead>
                <tr>
                    <th scope="col" class="bg-primary">Nama</th>
                    <th scope="col" class="bg-primary">Status</th>
                    <th scope="col" class="bg-primary">Waktu</th>
                    <th scope="col" class="bg-primary">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($perizinan as $item)
                    <tr>
                        <th scope="row">
                            <div class="d-flex align-items-center gap-2">
                                <span>{{ $item->absensi->user->nama }}</span>
                                @if (!$item->di_lihat)
                                    <span class="badge bg-danger">Baru</span>
                                @endif
                            </div>
                        </th>
                        <td>
                            <i @class([
                                'bi',
                                'bi-check-circle-fill text-primary' => $item->status_izin,
                                'bi-x-circle-fill text-danger' => !$item->status_izin,
                            ])></i>
                        </td>
                        <td>{{ $item->created_at }}</td>
                        <td>
                            <a class="btn btn-sm btn-primary" href="{{ route('perizinan.show', $item) }}">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">
                            <span>Tidak ada data.</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
