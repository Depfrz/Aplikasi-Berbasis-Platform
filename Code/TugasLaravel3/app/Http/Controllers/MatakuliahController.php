<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Matakuliah;

class MatakuliahController extends Controller
{
    public function index()
    {
        try {
            $matakuliah = Matakuliah::all();
            return response()->json([
                'status' => 'success',
                'data' => $matakuliah
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data mata kuliah: ' . $e->getMessage()
            ], 500);
        }
    }
}
