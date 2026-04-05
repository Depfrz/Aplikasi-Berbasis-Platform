@extends('layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('mahasiswa.index') }}">Data Mahasiswa</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail Mahasiswa</li>
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Detail Mahasiswa</h1>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        Informasi Mahasiswa
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-sm-3 fw-bold">NIM</div>
            <div class="col-sm-9">{{ $mahasiswa->nim }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-3 fw-bold">Nama</div>
            <div class="col-sm-9">{{ $mahasiswa->nama }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-3 fw-bold">Email</div>
            <div class="col-sm-9">{{ $mahasiswa->email }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-3 fw-bold">Prodi</div>
            <div class="col-sm-9">{{ $mahasiswa->prodi }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-3 fw-bold">Kelas</div>
            <div class="col-sm-9">{{ $mahasiswa->kelas }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-3 fw-bold">Dibuat Pada</div>
            <div class="col-sm-9">{{ $mahasiswa->created_at->format('d M Y H:i') }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-3 fw-bold">Terakhir Diupdate</div>
            <div class="col-sm-9">{{ $mahasiswa->updated_at->format('d M Y H:i') }}</div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <a href="{{ route('mahasiswa.edit', $mahasiswa->id) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection
