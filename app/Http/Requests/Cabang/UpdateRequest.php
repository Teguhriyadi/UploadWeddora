<?php

namespace App\Http\Requests\Cabang;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama' => 'required',
            'kota' => 'required',
            'alamat' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Nama Cabang Wajib Diisi',
            'kota.required' => 'Kota Cabang Wajib Diisi',
            'alamat.required' => 'Alamat Cabang Wajib Diisi'
        ];
    }
}
