<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DosenStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nip' => 'required|unique:dosens,nip',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:dosens,email',
        ];
    }
}
