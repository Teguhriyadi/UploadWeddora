<?php

namespace App\Http\Requests\TitipKado;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'guest_id' => 'nullable|exists:guest,id|required_without:guest_public_id',
            'guest_public_id' => 'nullable|exists:guest_public,id|required_without:guest_id',
            'nama_kado' => 'required|string|max:255',
            'qty' => 'required|integer|min:1',
            'keterangan' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'guest_id.required_without' => 'Nama tamu wajib dipilih.',
            'guest_id.exists' => 'Tamu undangan yang dipilih tidak valid.',
            'guest_public_id.required_without' => 'Nama tamu wajib dipilih.',
            'guest_public_id.exists' => 'Tamu luar yang dipilih tidak valid.',

            'nama_kado.required' => 'Nama kado wajib diisi.',
            'nama_kado.max' => 'Nama kado maksimal 255 karakter.',

            'qty.required' => 'QTY wajib diisi.',
            'qty.integer' => 'QTY harus berupa angka bulat.',
            'qty.min' => 'QTY minimal 1.',

            'keterangan.required' => 'Keterangan wajib diisi.',
        ];
    }
}
