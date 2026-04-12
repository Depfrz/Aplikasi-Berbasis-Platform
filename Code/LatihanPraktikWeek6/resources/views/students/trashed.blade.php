@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0">Trashed Student Records</h3>
                <a href="{{ route('students.index') }}" class="btn btn-secondary btn-sm">Back to Student List</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>NIM</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Study Program</th>
                            <th>Deleted At</th>
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
                                <td>{{ $student->study_program }}</td>
                                <td>{{ $student->deleted_at->format('Y-m-d H:i') }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <form action="{{ route('students.restore', $student->id) }}" method="POST" onsubmit="return confirm('Restore this student record?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Restore</button>
                                        </form>
                                        <form action="{{ route('students.force-delete', $student->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record permanently?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete Permanently</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Trash bin is empty.</td>
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