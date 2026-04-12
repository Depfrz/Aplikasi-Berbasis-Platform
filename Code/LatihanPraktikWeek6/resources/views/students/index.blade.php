@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0">Student List</h3>
                <a href="{{ route('students.create') }}" class="btn btn-primary">Add New Student</a>
            </div>

            <form action="{{ route('students.index') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by NIM, Name, Email, or Study Program..." value="{{ request('search') }}">
                    <button class="btn btn-outline-primary" type="submit">Search</button>
                    @if(request('search'))
                        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Clear</a>
                    @endif
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>
                                <a href="{{ route('students.index', array_merge(request()->query(), ['sort' => 'nim', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                    NIM {!! request('sort') === 'nim' ? (request('direction') === 'asc' ? '↑' : '↓') : '' !!}
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('students.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                    Name {!! request('sort') === 'name' ? (request('direction') === 'asc' ? '↑' : '↓') : '' !!}
                                </a>
                            </th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Study Program</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>{{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}</td>
                                <td>{{ $student->nim }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->phone_number }}</td>
                                <td>{{ $student->study_program }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('Are you sure you want to move this student to trash?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No students found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $students->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection