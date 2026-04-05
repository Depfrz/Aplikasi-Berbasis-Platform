<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MahasiswaUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nim' => 'required|unique:mahasiswas,nim,' . $this->mahasiswa,
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:mahasiswas,email,' . $this->mahasiswa,
            'prodi' => 'required|string',
            'kelas' => 'required|string',
        ];
    }
}
