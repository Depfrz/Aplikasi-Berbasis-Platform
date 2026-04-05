<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MatakuliahResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'kode_mk' => $this->kode_mk,
            'nama' => $this->nama,
            'sks' => $this->sks,
            'dosens' => $this->whenLoaded('dosens'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
