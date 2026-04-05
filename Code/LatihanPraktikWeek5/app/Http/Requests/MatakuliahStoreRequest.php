<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MatakuliahStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'kode_mk' => 'required|unique:matakuliahs,kode_mk|string|max:10',
            'nama' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
        ];
    }
}
