<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama' => 'required',
            'username' => 'required|max:30|unique:users,username',
            'email' => 'required',
            'role_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Nama Wajib Diisi',
            'username.required' => "Username Wajib Diisi",
            'username.unique' => 'Username Sudah Ada',
            'username.max' => 'Username Maksimal 50 Karakter',
            'email.required' => 'Email Wajib Diisi',
            'role_id.required' => 'Nama Role Wajib Diisi'
        ];
    }
}
