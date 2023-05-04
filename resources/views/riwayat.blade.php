@extends('layouts.app')

@section('content')
    <style>
        .calendar {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
        }

        .calendar-card {
            min-height: 80px;
        }

        .calendar-card.clickable:hover {
            background: #efefef;
            cursor: pointer;
        }
    </style>
    <h5 class="py-1">Riwayat Absensi</h5>
    <hr>
    <small class="d-inline-block mb-2">Menampilkan Periode : {{ $periode }}</small>
    <div class="calendar">
        @foreach (now()->getDays() as $day)
            <div class="card p-2 text-center border m-1 bg-black text-white">
                <small>{{ substr($hari[$day], 0, 3) }}</small>
            </div>
        @endforeach

        @for ($i = 0; $i < $selisih; $i++)
            <div class="card calendar-card opacity-25 p-2 m-1 gap"></div>
        @endfor

        @foreach ($dataAbsensi as $item)
            @if ($item['data'])
                <a @class([
                    'card calendar-card p-2 m-1 text-decoration-none clickable justify-content-between',
                    'border border-primary bg-primary bg-opacity-25' => $item['hari_ini'],
                    'bg-danger bg-opacity-25' => $item['hari_minggu'],
                ]) href="{{ route('riwayat.detail', $item['data']) }}">
                    <small class="text-muted">{{ $item['tanggal'] }}</small>
                    <div class="d-flex flex-column flex-md-row gap-1">
                        @if ($item['izin'])
                            <span class="badge border text-black bg-primary"> </span>
                        @else
                            @if ($item['masuk'])
                                @if ($item['telat'] > 0)
                                    <span class="badge border text-black bg-warning"> </span>
                                @else
                                    <span class="badge border text-black bg-success"> </span>
                                @endif
                            @endif
                            @if ($item['keluar'])
                                <span class="badge border text-black bg-danger"> </span>
                            @endif
                        @endif
                    </div>
                </a>
            @else
                <div @class([
                    'card calendar-card p-2 m-1 text-decoration-none',
                    'border border-primary bg-primary bg-opacity-25' => $item['hari_ini'],
                    'bg-danger bg-opacity-25' => $item['hari_minggu'],
                ])>
                    <small class="text-muted">{{ $item['tanggal'] }}</small>
                </div>
            @endif
        @endforeach
    </div>
    <hr>
    <div class="d-flex justify-content-between align-items-center">
        <a href="{{ route('riwayat', ['m' => $bulanSebelumnya]) }}" class="btn btn-sm btn-secondary">
            <i class="bi bi-arrow-left"></i> Bulan Sebelumnya
        </a>
        <a href="{{ route('riwayat', ['m' => $bulanSelanjutnya]) }}" class="btn btn-sm btn-secondary">
            Bulan Selanjutnya <i class="bi bi-arrow-right"></i>
        </a>
    </div>
@endsection
