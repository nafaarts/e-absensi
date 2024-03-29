@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="m-0">Detail Izin</h5>
        @if (!$perizinan->status_izin)
            <button @class([
                'btn btn-sm',
                'btn-success' => !$perizinan->status_izin,
                'btn-danger text-white' => $perizinan->status_izin,
            ]) onclick="document.getElementById('form-update-izin').submit()">
                {{ $perizinan->status_izin ? 'Batalkan Perizinan' : 'Izinkan' }}
            </button>
        @endif
    </div>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th scope="row">Nama</th>
                    <td>{{ $perizinan->absensi->user->nama }}</td>
                </tr>
                <tr>
                    <th scope="row">NIP</th>
                    <td>{{ $perizinan->absensi->user->nip }}</td>
                </tr>
                <tr>
                    <th scope="row">Profesi</th>
                    <td>{{ $perizinan->absensi->user->jabatan }}</td>
                </tr>
                <tr>
                    <th scope="row">Status</th>
                    <td>{{ $perizinan->status_izin ? 'DIIZINKAN' : 'BELUM DIIZINKAN' }}</td>
                </tr>
                <tr>
                    <th scope="row">Tanggal</th>
                    <td>
                        @php
                            $range = $perizinan->kustom_tanggal ? json_decode($perizinan->kustom_tanggal, true) : null;
                        @endphp
                        @if ($range)
                            {{ $range['dari'] }}
                            @if ($range['sampai'])
                                <span>sampai</span> {{ $range['sampai'] }}
                            @endif
                        @else
                            {{ $perizinan->created_at->format('Y-m-d') }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th scope="row">Kategori</th>
                    <td>{{ $perizinan->kategori_izin }}</td>
                </tr>
                <tr>
                    <th scope="row">Surat</th>
                    <td>
                        <a href="{{ asset('storage/perizinan/' . $perizinan->surat_izin) }}" target="_blank">
                            <i class="bi bi-eye"></i> Lihat
                        </a>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Alasan</th>
                    <td style="white-space: normal">{{ $perizinan->alasan_izin }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('perizinan.index') }}" class="btn btn-sm btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    <form action="{{ route('perizinan.update', $perizinan) }}" method="POST" id="form-update-izin">
        @csrf
        @method('PUT')
    </form>
@endsection
