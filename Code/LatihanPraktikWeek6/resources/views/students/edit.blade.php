@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0">Edit Student: {{ $student->name }}</h3>
                <a href="{{ route('students.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
            </div>

            <form action="{{ route('students.update', $student) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="nim" class="form-label">NIM (Student ID)</label>
                    <input type="text" name="nim" class="form-control @error('nim') is-invalid @enderror" id="nim" value="{{ old('nim', $student->nim) }}" required>
                    @error('nim')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name', $student->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email', $student->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" value="{{ old('phone_number', $student->phone_number) }}" required>
                    @error('phone_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="study_program" class="form-label">Study Program</label>
                    <input type="text" name="study_program" class="form-control @error('study_program') is-invalid @enderror" id="study_program" value="{{ old('study_program', $student->study_program) }}" required>
                    @error('study_program')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Update Student</button>
                    <a href="{{ route('students.index') }}" class="btn btn-outline-secondary text-center">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection