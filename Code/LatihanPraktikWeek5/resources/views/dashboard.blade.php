@extends('layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3 shadow">
            <div class="card-header">Total Mahasiswa</div>
            <div class="card-body">
                <h5 class="card-title">50</h5>
                <p class="card-text">Mahasiswa terdaftar aktif.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3 shadow">
            <div class="card-header">Total Dosen</div>
            <div class="card-body">
                <h5 class="card-title">20</h5>
                <p class="card-text">Dosen pengajar aktif.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info mb-3 shadow">
            <div class="card-header">Total Mata Kuliah</div>
            <div class="card-body">
                <h5 class="card-title">30</h5>
                <p class="card-text">Mata kuliah tersedia.</p>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <h3>Selamat Datang di Sistem Akademik</h3>
    <p>Gunakan menu di samping untuk mengelola data akademik.</p>
</div>
@endsection
