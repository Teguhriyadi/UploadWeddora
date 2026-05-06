<?php

namespace App\Http\Requests\Guest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'kategori_id' => 'required|exists:kategori,id',
            'nama_tamu' => 'required',
            'keluarga' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'kategori_id.required' => 'Nama Kategori Wajib Diisi',
            'nama_tamu.required' => 'Kategori Tamu Wajib Diisi',
            'keluarga.required' => 'Keluarga Wajib Diisi'
        ];
    }
}
