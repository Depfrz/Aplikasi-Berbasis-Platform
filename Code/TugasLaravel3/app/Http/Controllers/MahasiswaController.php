<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Validator;

class MahasiswaController extends Controller
{
    public function index()
    {
        try {
            $mahasiswa = Mahasiswa::all();
            return response()->json([
                'status' => 'success',
                'data' => $mahasiswa
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data mahasiswa: ' . $e->getMessage()
            ], 500);
        }
    }

    public function webIndex()
    {
        $mahasiswa = Mahasiswa::all();
        return view('mahasiswa.index', compact('mahasiswa'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|unique:mahasiswas,nim',
            'jurusan' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $mahasiswa = Mahasiswa::create($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Data mahasiswa berhasil disimpan',
                'data' => $mahasiswa
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data mahasiswa: ' . $e->getMessage()
            ], 500);
        }
    }
}
