@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="m-0">Daftar User</h5>
        <div>
            <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus"></i> Tambah User
            </a>
        </div>
    </div>
    <hr>
    @if (session('berhasil'))
        <div class="alert alert-success" role="alert">
            <strong>Berhasil!</strong> {{ session('berhasil') }}
        </div>
    @endif
    <form action="{{ route('users.index') }}" method="GET">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Cari user" name="cari">
            <button class="btn btn-outline-secondary" type="submit" id="button-addon2">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col" class="bg-primary">Nama</th>
                    <th scope="col" class="bg-primary">NIP</th>
                    <th scope="col" class="bg-primary">Email</th>
                    <th scope="col" class="bg-primary">Profesi</th>
                    <th scope="col" class="bg-primary">Tipe</th>
                    <th scope="col" class="bg-primary">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $item)
                    <tr>
                        <th scope="row">{{ $item->nama }}</th>
                        <td>{{ $item->nip }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->jabatan }}</td>
                        <td>{{ $item->hak_akses }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a class="btn btn-sm btn-primary" href="{{ route('users.riwayat', $item) }}">
                                    <i class="bi bi-calendar-week"></i>
                                </a>
                                <a class="btn btn-sm btn-warning" href="{{ route('users.edit', $item) }}">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <div>
                                    <form action="{{ route('users.destroy', $item) }}" method="post"
                                        onsubmit="return confirm('apa kamu yakin untuk menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            <span>Tidak ada data.</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $users->links() }}
@endsection
