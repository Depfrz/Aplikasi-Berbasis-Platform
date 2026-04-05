@extends('layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('matakuliah.index') }}">Data Mata Kuliah</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail Mata Kuliah</li>
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Detail Mata Kuliah</h1>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
        Informasi Mata Kuliah
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-sm-3 fw-bold">Kode MK</div>
            <div class="col-sm-9"><span class="badge bg-secondary">{{ $matakuliah->kode_mk }}</span></div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-3 fw-bold">Nama</div>
            <div class="col-sm-9">{{ $matakuliah->nama }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-3 fw-bold">SKS</div>
            <div class="col-sm-9">{{ $matakuliah->sks }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-3 fw-bold">Dosen Pengampu</div>
            <div class="col-sm-9">
                @if($matakuliah->dosens->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($matakuliah->dosens as $dosen)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $dosen->nama }} ({{ $dosen->nip }})
                                <a href="{{ route('dosen.show', $dosen->id) }}" class="btn btn-sm btn-link">Lihat Profil</a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <span class="text-muted italic">Belum ada dosen pengampu.</span>
                @endif
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <a href="{{ route('matakuliah.edit', $matakuliah->id) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('matakuliah.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection
