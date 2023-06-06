@extends('layouts.app')

@section('content')
    <h5 class="py-1">Daftar Jadwal</h5>
    <hr>
    @if (session('berhasil'))
        <div class="alert alert-success" role="alert">
            <strong>Berhasil!</strong> {{ session('berhasil') }}
        </div>
    @endif
    <div class="table-responsive">
        <table class="table table-bordered table-striped-columns">
            <thead>
                <tr>
                    <th scope="col" class="bg-primary">Hari</th>
                    <th scope="col" class="bg-primary">Jam Masuk</th>
                    <th scope="col" class="bg-primary">Jam Keluar</th>
                    <th scope="col" class="bg-primary">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jadwal as $item)
                    <tr>
                        <th scope="row" class="text-uppercase">{{ $item->hari }}</th>
                        <td>{{ $item->jam_masuk }}</td>
                        <td>{{ $item->jam_keluar }}</td>
                        <td>
                            <a class="btn btn-sm btn-warning" href="{{ route('jadwal.edit', $item) }}">
                                <i class="bi bi-pencil"></i>
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
