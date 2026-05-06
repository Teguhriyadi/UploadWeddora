<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama_role' => 'required|max:50|unique:role,nama_role'
        ];
    }

    public function messages()
    {
        return [
            'nama_role.required' => 'Nama Role Wajib Diisi',
            'nama_role.unique' => 'Nama Role Sudah Ada',
            'nama_role.max' => 'Nama Role Maksimal 50 Karakter'
        ];
    }
}
