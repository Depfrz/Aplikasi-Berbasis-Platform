@extends('layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Data Mahasiswa</li>
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Data Mahasiswa</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('mahasiswa.create') }}" class="btn btn-sm btn-outline-primary">Tambah Mahasiswa</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Prodi</th>
                <th>Kelas</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mahasiswas as $index => $mahasiswa)
            <tr>
                <td>{{ ($mahasiswas->currentPage() - 1) * $mahasiswas->perPage() + $loop->iteration }}</td>
                <td>{{ $mahasiswa->nim }}</td>
                <td>{{ $mahasiswa->nama }}</td>
                <td>{{ $mahasiswa->email }}</td>
                <td>{{ $mahasiswa->prodi }}</td>
                <td>{{ $mahasiswa->kelas }}</td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="{{ route('mahasiswa.show', $mahasiswa->id) }}" class="btn btn-sm btn-info text-white">Show</a>
                        <a href="{{ route('mahasiswa.edit', $mahasiswa->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('mahasiswa.destroy', $mahasiswa->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Data kosong.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $mahasiswas->links('pagination::bootstrap-5') }}
</div>
@endsection
