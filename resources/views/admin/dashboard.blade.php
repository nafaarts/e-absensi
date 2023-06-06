@extends('layouts.app')

@section('content')
    <div class="row mb-2">
        <div class="col-md-3 pb-2">
            <a href="{{ route('users.index') }}" class="text-decoration-none">
                <div class="card bg-primary rounded-0 p-3 clickable">
                    <small class="text-black">Total User</small>
                    <h2 class="m-0 text-black">{{ $totalUser }}</h2>
                </div>
            </a>
        </div>
        <div class="col-md-3 pb-2">
            <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#modalUserHadir">
                <div class="card bg-success rounded-0 p-3 clickable">
                    <small class="text-black">Total Hadir (hari ini)</small>
                    <h2 class="m-0 text-black">{{ $totalHadir }}</h2>
                </div>
            </a>
        </div>
        <div class="col-md-3 pb-2">
            <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#modalUserTidakHadir">
                <div class="card bg-danger rounded-0 p-3 clickable">
                    <small class="text-black">Total Tidak Hadir (hari ini)</small>
                    <h2 class="m-0 text-black">{{ $totalTidakHadir }}</h2>
                </div>
            </a>
        </div>
        <div class="col-md-3 pb-2">
            <a href="{{ route('perizinan.index') }}" class="text-decoration-none">
                <div class="card bg-warning rounded-0 p-3 clickable">
                    <small class="text-black">Total Izin</small>
                    <h2 class="m-0 text-black">{{ $totalIzin }}</h2>
                </div>
            </a>
        </div>
    </div>

    <style>
        .clickable:hover {
            background: lightgray;
            cursor: pointer;
        }
    </style>

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

    <!-- Modal -->
    <div class="modal fade" id="modalUserTidakHadir" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalUserTidakHadirLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalUserTidakHadirLabel">User Tidak Hadir</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Nama</th>
                                    <th scope="col">NIP</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Jabatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($userTidakHadir as $item)
                                    <tr>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->nip }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->jabatan }}</td>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalUserHadir" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalUserHadirLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalUserHadirLabel">User Hadir</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Nama</th>
                                    <th scope="col">NIP</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Jabatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($userHadir as $item)
                                    <tr>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->nip }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->jabatan }}</td>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
