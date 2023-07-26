@extends('layouts.app')

@section('content')
    <h5 class="py-1">Detail Riwayat Absensi</h5>
    <hr>
    <div class="table-responsive mb-2">
        <table class="table table-bordered">
            <tbody>
                <tr class="bg-primary text-white">
                    <th colspan="2">Data User</th>
                </tr>
                <tr>
                    <th scope="row">Nama</th>
                    <td>{{ $absensi->user->nama }}</td>
                </tr>
                <tr>
                    <th scope="row">Email</th>
                    <td>{{ $absensi->user->email }}</td>
                </tr>
                <tr>
                    <th scope="row">NIP</th>
                    <td>{{ $absensi->user->nip }}</td>
                </tr>
                <tr>
                    <th scope="row">Profesi</th>
                    <td>{{ $absensi->user->jabatan }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="row mb-2">
        @if ($absensi->logMasuk)
            <div class="col-md-6">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr class="bg-primary text-white">
                                <th colspan="2">Data Masuk</th>
                            </tr>
                            <tr>
                                <th scope="row">Jam Masuk</th>
                                <td>{{ $absensi->logMasuk->created_at }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Terlambat</th>
                                <td>{{ $absensi->menit_terlambat }} Menit</td>
                            </tr>
                            <tr>
                                <th scope="row">Latitude</th>
                                <td>{{ $absensi->logMasuk->latitude }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Longitude</th>
                                <td>{{ $absensi->logMasuk->longitude }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Foto</th>
                                <td>
                                    <img src="{{ asset('storage/img/absensi/' . $absensi->logMasuk->foto) }}"
                                        alt="foto masuk" height="100">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        @if ($absensi->logKeluar)
            <div class="col-md-6">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr class="bg-primary text-white">
                                <th colspan="2">Data Masuk</th>
                            </tr>
                            <tr>
                                <th scope="row">Jam Masuk</th>
                                <td>{{ $absensi->logKeluar->created_at }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Lembur</th>
                                <td>{{ $absensi->menit_lembur }} Menit</td>
                            </tr>
                            <tr>
                                <th scope="row">Latitude</th>
                                <td>{{ $absensi->logKeluar->latitude }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Longitude</th>
                                <td>{{ $absensi->logKeluar->longitude }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Foto</th>
                                <td>
                                    <img src="{{ asset('storage/img/absensi/' . $absensi->logKeluar->foto) }}"
                                        alt="foto keluar" height="100">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
    @if ($absensi->izin)
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr class="bg-primary text-white">
                        <th colspan="2">Data Perizinan</th>
                    </tr>
                    <tr>
                        <th scope="row">Izin dibuat</th>
                        <td>{{ $absensi->izin->created_at }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Status</th>
                        <td>{{ $absensi->izin->status_izin ? 'Disetujui' : 'Tidak disetujui' }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Surat Izin</th>
                        <td>
                            <a href="{{ asset('storage/perizinan/' . $absensi->izin->surat_izin) }}" target="_blank">
                                <i class="bi bi-eye"></i> Lihat
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Alasan</th>
                        <td>{{ $absensi->izin->alasan_izin }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif
    <hr>
    <a href="{{ route('users.riwayat', $user) }}" class="btn btn-sm btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
@endsection
