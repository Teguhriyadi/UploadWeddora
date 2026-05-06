<?php

namespace App\Http\Requests\Kategori;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama_kategori' => 'required|max:50|unique:kategori,nama_kategori'
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
