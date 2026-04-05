@extends('layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('matakuliah.index') }}">Data Mata Kuliah</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah Mata Kuliah</li>
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tambah Mata Kuliah</h1>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('matakuliah.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="kode_mk" class="form-label">Kode MK</label>
                <input type="text" class="form-control @error('kode_mk') is-invalid @enderror" id="kode_mk" name="kode_mk" value="{{ old('kode_mk') }}" placeholder="Contoh: CS101" required>
                @error('kode_mk')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Mata Kuliah</label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="sks" class="form-label">SKS (1-6)</label>
                <input type="number" class="form-control @error('sks') is-invalid @enderror" id="sks" name="sks" value="{{ old('sks', 1) }}" min="1" max="6" required>
                @error('sks')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="dosen_ids" class="form-label">Dosen Pengampu (Multiple)</label>
                <select name="dosen_ids[]" id="dosen_ids" class="form-select @error('dosen_ids') is-invalid @enderror" multiple>
                    @foreach($dosens as $dosen)
                        <option value="{{ $dosen->id }}" {{ (collect(old('dosen_ids'))->contains($dosen->id)) ? 'selected' : '' }}>
                            {{ $dosen->nama }} ({{ $dosen->nip }})
                        </option>
                    @endforeach
                </select>
                <div class="form-text">Tekan Ctrl (Windows) atau Command (Mac) untuk memilih lebih dari satu.</div>
                @error('dosen_ids')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('matakuliah.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
