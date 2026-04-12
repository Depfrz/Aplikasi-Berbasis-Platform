<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('study_program', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        $students = $query->paginate(10)->withQueryString();

        return view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(StoreStudentRequest $request)
    {
        Student::create($request->validated());

        return redirect()->route('students.index')
            ->with('success', 'Student created successfully.');
    }

    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        $student->update($request->validated());

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully (Soft Delete).');
    }

    public function trashed()
    {
        $students = Student::onlyTrashed()->paginate(10);
        return view('students.trashed', compact('students'));
    }

    public function restore($id)
    {
        $student = Student::onlyTrashed()->findOrFail($id);
        $student->restore();

        return redirect()->route('students.trashed')
            ->with('success', 'Student restored successfully.');
    }

    public function forceDelete($id)
    {
        $student = Student::onlyTrashed()->findOrFail($id);
        $student->forceDelete();

        return redirect()->route('students.trashed')
            ->with('success', 'Student deleted permanently.');
    }
}
