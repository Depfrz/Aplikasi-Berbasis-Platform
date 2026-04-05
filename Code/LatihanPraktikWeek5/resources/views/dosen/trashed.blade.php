@extends('layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dosen.index') }}">Data Dosen</a></li>
    <li class="breadcrumb-item active" aria-current="page">Dosen Terhapus</li>
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Data Dosen Terhapus (Soft Deleted)</h1>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>NIP</th>
                <th>Nama</th>
                <th>Email</th>
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
                <td>
                    <form action="{{ route('dosen.restore', $dosen->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">Restore</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data terhapus.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $dosens->links('pagination::bootstrap-5') }}
</div>
@endsection
