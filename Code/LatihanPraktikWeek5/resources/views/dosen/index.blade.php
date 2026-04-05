@extends('layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Data Dosen</li>
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Data Dosen</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('dosen.create') }}" class="btn btn-sm btn-outline-primary">Tambah Dosen</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>NIP</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Username</th>
                <th>Matakuliah</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dosens as $index => $dosen)
            <tr>
                <td>{{ ($dosens->currentPage() - 1) * $dosens->perPage() + $loop->iteration }}</td>
                <td>{{ $dosen->nip }}</td>
                <td>{{ $dosen->nama }}</td>
                <td>{{ $dosen->email }}</td>
                <td>{{ $dosen->username }}</td>
                <td>
                    @foreach($dosen->matakuliahs as $mk)
                        <span class="badge bg-secondary">{{ $mk->nama }}</span>
                    @endforeach
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="{{ route('dosen.show', $dosen->id) }}" class="btn btn-sm btn-info text-white">Show</a>
                        @can('update', $dosen)
                        <a href="{{ route('dosen.edit', $dosen->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        @endcan
                        @can('delete', $dosen)
                        <form action="{{ route('dosen.destroy', $dosen->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                        @endcan
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
    {{ $dosens->links('pagination::bootstrap-5') }}
</div>
@endsection
