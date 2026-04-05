@extends('layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Data Mata Kuliah</li>
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Data Mata Kuliah</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('matakuliah.create') }}" class="btn btn-sm btn-outline-primary">Tambah Mata Kuliah</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Kode MK</th>
                <th>Nama</th>
                <th>SKS</th>
                <th>Dosen Pengampu</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($matakuliahs as $index => $mk)
            <tr>
                <td>{{ ($matakuliahs->currentPage() - 1) * $matakuliahs->perPage() + $loop->iteration }}</td>
                <td>{{ $mk->kode_mk }}</td>
                <td>{{ $mk->nama }}</td>
                <td>{{ $mk->sks }}</td>
                <td>
                    @foreach($mk->dosens as $dosen)
                        <span class="badge bg-primary">{{ $dosen->nama }}</span>
                    @endforeach
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="{{ route('matakuliah.show', $mk->id) }}" class="btn btn-sm btn-info text-white">Show</a>
                        <a href="{{ route('matakuliah.edit', $mk->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('matakuliah.destroy', $mk->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Data kosong.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $matakuliahs->links('pagination::bootstrap-5') }}
</div>
@endsection
