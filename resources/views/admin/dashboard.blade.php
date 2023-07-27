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

        .kotak {
            height: 10px;
            width: 10px;
            border-radius: 2px;
        }

        .terlambat {
            background: orange;
        }

        .masuk {
            background: green;
        }

        .keluar {
            background: blue;
        }
    </style>

    <div class="card mt-3 p-3 border mb-4">
        <small>Menampilan hasil dari : <strong>{{ $usersAttendances['periode'] }}</strong></small>
        <div class="table-responsive my-3">
            <table class="table table-compact">
                <thead>
                    <tr>
                        @foreach ($usersAttendances['labels'] as $label)
                            <th scope="row">
                                {{ $label }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usersAttendances['data'] as $data)
                        <tr>
                            <th class="text-xs d:text-sm">
                                <a href="{{ route('users.riwayat', $data->userId) }}"
                                    class="text-black">{{ $data->nama }}</a>
                            </th>
                            @foreach ($data->absensi as $absensi)
                                <td>
                                    @if ($absensi->izin)
                                        {{-- <i class="bi bi-envelope-check-fill"></i> --}}
                                        <b class="text-primary">{{ $absensi->izinKategori }}</b>
                                    @else
                                        @if ($absensi->masuk)
                                            <div class="d-flex flex-column" style="gap: 2px;">
                                                @if ($absensi->terlambat)
                                                    <div class="kotak terlambat"></div>
                                                @else
                                                    <div class="kotak masuk"></div>
                                                @endif

                                                @if ($absensi->keluar)
                                                    <div class="kotak keluar"></div>
                                                @endif
                                            </div>
                                        @else
                                            <span>-</span>
                                        @endif
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $usersAttendances['data']->links() }}
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped mb-0">
            <thead>
                <tr>
                    <th scope="col" class="bg-primary">Waktu</th>
                    <th scope="col" class="bg-primary">Nama</th>
                    <th scope="col" class="bg-primary">Profesi</th>
                    <th scope="col" class="bg-primary">Deskripsi</th>
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
                                    <th scope="col">Profesi</th>
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
                                    <th scope="col">Profesi</th>
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
