<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MatakuliahUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'kode_mk' => 'required|string|max:10|unique:matakuliahs,kode_mk,' . $this->matakuliah,
            'nama' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
        ];
    }
}
