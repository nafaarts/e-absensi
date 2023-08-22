@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="m-0">Perizinan</h5>
        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#buatPerizinan">
            <i class="bi bi-plus"></i>
            Buat Perizinan
        </button>
    </div>
    <hr>
    @if (session('berhasil'))
        <div class="alert alert-success" role="alert">
            <strong>Berhasil!</strong> {{ session('berhasil') }}
        </div>
    @endif

    @if (session('gagal'))
        <div class="alert alert-danger" role="alert">
            <strong>Gagal!</strong> {{ session('gagal') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Status</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Katagori</th>
                    <th scope="col">Alasan</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($perizinan as $item)
                    <tr @class(['fw-bold' => !$item->di_lihat])>
                        <td>
                            <i @class([
                                'bi',
                                'bi-check-circle-fill' => $item->status_izin,
                                'bi-x-circle-fill' => !$item->status_izin,
                            ])></i>
                        </td>
                        <td>
                            @php
                                $range = $item->kustom_tanggal ? json_decode($item->kustom_tanggal, true) : null;
                            @endphp
                            @if ($range)
                                {{ $range['dari'] }}
                                @if ($range['sampai'])
                                    <span>sampai</span> {{ $range['sampai'] }}
                                @endif
                            @else
                                {{ $item->created_at->format('Y-m-d') }}
                            @endif
                        </td>
                        <td>{{ $item->kategori_izin }}</td>
                        <td>
                            <span class="text-truncate" style="max-width: 300px">
                                {{ $item->alasan_izin }}
                            </span>
                        </td>
                        <td>
                            <a class="btn btn-sm btn-primary" href="{{ route('izin.show', $item) }}">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            <span>Tidak ada data.</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="buatPerizinan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="buatPerizinanLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('izin.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="buatPerizinanLabel">Buat Perizinan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kategori Izin</label>
                        <br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="kategori_izin" id="IZIN"
                                value="IZIN" @checked(old('kategori_izin') == 'IZIN')>
                            <label class="form-check-label" for="IZIN">IZIN</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="kategori_izin" id="SAKIT"
                                value="SAKIT" @checked(old('kategori_izin') == 'SAKIT')>
                            <label class="form-check-label" for="SAKIT">SAKIT</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="kategori_izin" id="CUTI"
                                value="CUTI" @checked(old('kategori_izin') == 'CUTI')>
                            <label class="form-check-label" for="CUTI">CUTI</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="kategori_izin" id="DINAS"
                                value="DINAS" @checked(old('kategori_izin') == 'DINAS')>
                            <label class="form-check-label" for="DINAS">DINAS</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="dari_tanggal" class="form-label">Tanggal <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="date" value="{{ old('dari_tanggal', date('Y-m-d')) }}"
                                    id="dari_tanggal" name="dari_tanggal">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sampai_tanggal" class="form-label">Sampai Tanggal</label>
                                <input class="form-control" type="date" value="{{ old('sampai_tanggal') }}"
                                    id="sampai_tanggal" name="sampai_tanggal">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="surat_izin" class="form-label">Bukti / Surat Izin <span
                                class="text-danger">*</span></label>
                        <input class="form-control" type="file" id="surat_izin" name="surat_izin">
                    </div>

                    <div class="mb-3">
                        <label for="alasan_izin" class="form-label">Alasan Izin <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control" id="alasan_izin" name="alasan_izin" rows="3">{{ old('alasan_izin') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </div>
            </form>
        </div>
    </div>
@endsection
