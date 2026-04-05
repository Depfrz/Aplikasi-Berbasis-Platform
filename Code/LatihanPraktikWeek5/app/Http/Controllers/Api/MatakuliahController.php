<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Matakuliah;
use App\Http\Resources\MatakuliahResource;
use App\Http\Requests\MatakuliahStoreRequest;
use App\Http\Requests\MatakuliahUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatakuliahController extends Controller
{
    public function index()
    {
        $matakuliahs = Matakuliah::with('dosens')->paginate(15);
        return MatakuliahResource::collection($matakuliahs);
    }

    public function store(MatakuliahStoreRequest $request)
    {
        try {
            $matakuliah = DB::transaction(function () use ($request) {
                $mk = Matakuliah::create($request->validated());
                if ($request->has('dosen_ids')) {
                    $mk->dosens()->sync($request->dosen_ids);
                }
                return $mk;
            });

            return (new MatakuliahResource($matakuliah->load('dosens')))
                ->response()
                ->setStatusCode(201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function show($id)
    {
        $matakuliah = Matakuliah::with('dosens')->findOrFail($id);
        return new MatakuliahResource($matakuliah);
    }

    public function update(MatakuliahUpdateRequest $request, $id)
    {
        $matakuliah = Matakuliah::findOrFail($id);
        
        try {
            DB::transaction(function () use ($request, $matakuliah) {
                $matakuliah->update($request->validated());
                if ($request->has('dosen_ids')) {
                    $matakuliah->dosens()->sync($request->dosen_ids);
                }
            });

            return new MatakuliahResource($matakuliah->load('dosens'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function destroy($id)
    {
        $matakuliah = Matakuliah::findOrFail($id);
        $matakuliah->delete();

        return response()->json(['message' => 'Matakuliah deleted successfully'], 200);
    }
}
