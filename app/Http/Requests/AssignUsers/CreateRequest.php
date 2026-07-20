<?php

namespace App\Http\Requests\AssignUsers;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'event_id' => 'required',
            'user_id' => 'required',
            'jabatan' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'event_id.required' => 'Nama Event Wajib Diisi',
            'user_id.required' => 'Nama User Wajib Diisi',
            'jabatan.required' => 'Jabatan Wajib Diisi'
        ];
    }
}
