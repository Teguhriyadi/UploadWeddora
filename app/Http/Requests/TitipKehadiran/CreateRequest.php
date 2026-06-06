<?php

namespace App\Http\Requests\TitipKehadiran;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'wakil_id' => 'required',
            'guest_id' => 'nullable|exists:guest,id',
            'nama_tamu' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'wakil_id.required' => 'Wakil Tamu Wajib Diisi',
            'guest_id.exists' => 'Tamu yang dipilih tidak valid.',
            'nama_tamu.string' => 'Nama tamu harus berupa teks.',
            'nama_tamu.max' => 'Nama tamu maksimal 255 karakter.'
        ];
    }
}
