@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center"
        style="gap: 20px">
        <div>
            <h5>Rekap Absensi</h5>
            <small>Menampilan hasil dari : <strong>{{ $usersAttendances['periode'] }}</strong></small>
        </div>
        <div>
            <form action="{{ route('rekap', request()->all()) }}" class="d-flex align-items-center" style="gap: 10px">
                <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                <div class="input-group">
                    <label class="input-group-text"><i class="bi bi-search"></i></label>
                    <input type="text" class="form-control" name="cari" placeholder="Cari"
                        value="{{ request('cari') }}">
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                    <input type="month" class="form-control" name="periode" placeholder="Bulan"
                        value="{{ $year . '-' . $month }}">
                </div>
                <button class="btn btn-primary">SUBMIT</button>
            </form>
        </div>
    </div>
    <hr>
    <div class="d-flex justify-content-between align-items-center" style="gap: 10px;">
        <form action="{{ route('rekap', request()->all()) }}" id="form-kategori">
            <input type="hidden" name="cari" value="{{ request('cari') }}">
            <input type="hidden" name="periode" value="{{ request('periode') }}">
            <div class="btn-group" role="group">
                <input type="radio" name="kategori" class="btn-check kategori-choices" id="all" value="all"
                    @checked(request('kategori') == 'all' || !request()->has('kategori'))>
                <label class="btn btn-sm btn-outline-dark" for="all">SEMUA</label>

                <input type="radio" name="kategori" class="btn-check kategori-choices" id="guru" value="guru"
                    @checked(request('kategori') == 'guru')>
                <label class="btn btn-sm btn-outline-dark" for="guru">GURU</label>

                <input type="radio" name="kategori" class="btn-check kategori-choices" id="pegawai" value="pegawai"
                    @checked(request('kategori') == 'pegawai')>
                <label class="btn btn-sm btn-outline-dark" for="pegawai">STAFF</label>
            </div>
        </form>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalExport">
            <i class="bi bi-file-earmark-excel"></i> Export
        </button>
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
                                        <i class="bi bi-envelope-check-fill"></i>
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

    <div class="modal fade" id="modalExport" tabindex="-1" aria-labelledby="modalExportLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalExportLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('export.absensi') }}" method="POST" id="form-export">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="periode">Periode</label>
                            <input type="month" class="form-control" id="periode" name="periode"
                                placeholder="Masukan periode" value="{{ $year . '-' . $month }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="kategori">Kategori</label>
                            <select name="kategori" id="kategori" class="form-select">
                                <option value="" disabled>Pilih kategori</option>
                                <option value="SEMUA">SEMUA</option>
                                <option value="GURU">GURU</option>
                                <option value="PEGAWAI">STAFF</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary"
                        onclick="document.getElementById('form-export').submit()">
                        <i class="bi bi-file-earmark-excel"></i> Export
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const formKategori = document.getElementById('form-kategori');
        const kategoris = document.querySelectorAll('.kategori-choices');
        kategoris.forEach(kategori => {
            kategori.onclick = () => formKategori.submit()
        })
    </script>
@endsection
