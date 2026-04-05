<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Http\Requests\DosenStoreRequest;
use App\Http\Requests\DosenUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DosenController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Dosen::class, 'dosen');
    }

    public function index()
    {
        $dosens = Dosen::with('matakuliahs')->paginate(15);
        return view('dosen.index', compact('dosens'));
    }

    public function create()
    {
        return view('dosen.create');
    }

    public function store(DosenStoreRequest $request)
    {
        $data = $request->validated();
        $data['username'] = Str::before($data['email'], '@');

        Dosen::create($data);

        return redirect()->route('dosen.index')
            ->with('success', 'Data dosen berhasil ditambahkan.');
    }

    public function show(Dosen $dosen)
    {
        $dosen->load('matakuliahs'); // Lazy loading
        return view('dosen.show', compact('dosen'));
    }

    public function edit(Dosen $dosen)
    {
        return view('dosen.edit', compact('dosen'));
    }

    public function update(DosenUpdateRequest $request, Dosen $dosen)
    {
        $dosen->update($request->validated());

        return redirect()->route('dosen.index')
            ->with('success', 'Data dosen berhasil diperbarui.');
    }

    public function destroy(Dosen $dosen)
    {
        $dosen->delete();

        return redirect()->route('dosen.index')
            ->with('success', 'Data dosen berhasil dihapus (Soft Delete).');
    }

    public function trashed()
    {
        $dosens = Dosen::onlyTrashed()->paginate(15);
        return view('dosen.trashed', compact('dosens'));
    }

    public function restore($id)
    {
        $dosen = Dosen::onlyTrashed()->findOrFail($id);
        $dosen->restore();

        return redirect()->route('dosen.trashed')
            ->with('success', 'Data dosen berhasil dipulihkan.');
    }
}
