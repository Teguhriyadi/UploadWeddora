<?php

namespace App\Http\Requests\Tema;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama' => 'required|unique:lp_tema,nama',
            'subtitle' => 'required',
            'deskripsi' => 'required',
            'badge' => 'required',
            'lp_kategori_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Nama Tema Wajib Diisi',
            'nama.unique' => 'Nama Tema Sudah Ada',
            'subtitle' => 'Subtitle Wajib Diisi',
            'deskripsi' => 'Deskripsi Wajib Diisi',
            'badge' => 'Badge Wajib Diisi',
            'lp_kategori_id' => 'Nama Kategori Wajib Diisi'
        ];
    }
}
