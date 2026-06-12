<?php

namespace App\Http\Requests\TitipKehadiran;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'wakil_id' => 'nullable|exists:guest,id|required_without:wakil_guest_public_id',
            'wakil_guest_public_id' => 'nullable|exists:guest_public,id|required_without:wakil_id',
            'guest_id' => 'nullable|exists:guest,id',
            'nama_tamu' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'wakil_id.required_without' => 'Wakil Tamu Wajib Diisi',
            'wakil_id.exists' => 'Wakil tamu undangan yang dipilih tidak valid.',
            'wakil_guest_public_id.required_without' => 'Wakil Tamu Wajib Diisi',
            'wakil_guest_public_id.exists' => 'Wakil tamu luar yang dipilih tidak valid.',
            'guest_id.exists' => 'Tamu yang dipilih tidak valid.',
            'nama_tamu.string' => 'Nama tamu harus berupa teks.',
            'nama_tamu.max' => 'Nama tamu maksimal 255 karakter.'
        ];
    }
}
