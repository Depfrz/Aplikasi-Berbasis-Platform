<?php

// Nama: Muhammad Daffa Fariza | NIM: 103012300004
namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Mahasiswa::query();

        if ($request->has('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $mahasiswas = $query->get();

        return response()->json($mahasiswas, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'nim' => 'required|string|unique:mahasiswas,nim',
            'email' => 'required|email',
            'prodi' => 'required|string',
            'ipk' => 'required|numeric',
            'sks' => 'required|integer',
        ]);

        $mahasiswa = Mahasiswa::create($validated);

        return response()->json($mahasiswa, 201);
    }

    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::find($id);

        if (!$mahasiswa) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'nama' => 'string',
            'nim' => 'string|unique:mahasiswas,nim,' . $id,
            'email' => 'email',
            'prodi' => 'string',
            'ipk' => 'numeric',
            'sks' => 'integer',
        ]);

        $mahasiswa->update($validated);

        return response()->json($mahasiswa, 200);
    }

    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::find($id);

        if (!$mahasiswa) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $mahasiswa->delete();

        return response()->json(['message' => 'Data berhasil dihapus'], 200);
    }
}
