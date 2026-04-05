<?php

namespace App\Http\Controllers;

use App\Models\Matakuliah;
use App\Models\Dosen;
use App\Http\Requests\MatakuliahStoreRequest;
use App\Http\Requests\MatakuliahUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatakuliahController extends Controller
{
    public function index()
    {
        $matakuliahs = Matakuliah::with('dosens')->paginate(15);
        return view('matakuliah.index', compact('matakuliahs'));
    }

    public function create()
    {
        $dosens = Dosen::all();
        return view('matakuliah.create', compact('dosens'));
    }

    public function store(MatakuliahStoreRequest $request)
    {
        DB::transaction(function () use ($request) {
            $matakuliah = Matakuliah::create($request->validated());
            if ($request->has('dosen_ids')) {
                $matakuliah->dosens()->sync($request->dosen_ids);
            }
        });

        return redirect()->route('matakuliah.index')
            ->with('success', 'Data mata kuliah berhasil ditambahkan.');
    }

    public function show(Matakuliah $matakuliah)
    {
        $matakuliah->load('dosens');
        return view('matakuliah.show', compact('matakuliah'));
    }

    public function edit(Matakuliah $matakuliah)
    {
        $dosens = Dosen::all();
        $matakuliah->load('dosens');
        return view('matakuliah.edit', compact('matakuliah', 'dosens'));
    }

    public function update(MatakuliahUpdateRequest $request, Matakuliah $matakuliah)
    {
        DB::transaction(function () use ($request, $matakuliah) {
            $matakuliah->update($request->validated());
            if ($request->has('dosen_ids')) {
                $matakuliah->dosens()->sync($request->dosen_ids);
            }
        });

        return redirect()->route('matakuliah.index')
            ->with('success', 'Data mata kuliah berhasil diperbarui.');
    }

    public function destroy(Matakuliah $matakuliah)
    {
        $matakuliah->delete();

        return redirect()->route('matakuliah.index')
            ->with('success', 'Data mata kuliah berhasil dihapus.');
    }

    public function assignDosen(Request $request, $id)
    {
        $request->validate([
            'dosen_ids' => 'required|array',
            'dosen_ids.*' => 'exists:dosens,id'
        ]);

        $matakuliah = Matakuliah::findOrFail($id);

        DB::transaction(function () use ($request, $matakuliah) {
            $matakuliah->dosens()->sync($request->dosen_ids);
        });

        return redirect()->back()->with('success', 'Dosen pengampu berhasil diperbarui.');
    }
}
