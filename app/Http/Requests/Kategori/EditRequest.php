<?php

namespace App\Http\Requests\Kategori;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama_kategori' => [
            'required',
            'max:50',
                Rule::unique('kategori', 'nama_kategori')->ignore($this->id)
            ],
        ];
    }

    public function messages()
    {
        return [
            'nama_kategori.required' => 'Nama Kategori Wajib Diisi',
            'nama_kategori.unique' => 'Nama Kategori Sudah Ada',
            'nama_kategori.max' => 'Nama Kategori Maksimal 50 Karakter'
        ];
    }
}
