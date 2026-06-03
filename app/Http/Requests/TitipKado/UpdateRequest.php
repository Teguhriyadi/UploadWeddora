<?php

namespace App\Http\Requests\TitipKado;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'guest_id' => 'nullable|exists:guests,id',
            'nama_tamu' => 'nullable|string|max:255',
            'nama_kado' => 'required|string|max:255',
            'qty' => 'required|integer|min:1',
            'keterangan' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'guest_id.exists' => 'Tamu yang dipilih tidak valid.',

            'nama_tamu.string' => 'Nama tamu harus berupa teks.',
            'nama_tamu.max' => 'Nama tamu maksimal 255 karakter.',

            'nama_kado.required' => 'Nama kado wajib diisi.',
            'nama_kado.max' => 'Nama kado maksimal 255 karakter.',

            'qty.required' => 'QTY wajib diisi.',
            'qty.integer' => 'QTY harus berupa angka bulat.',
            'qty.min' => 'QTY minimal 1.',

            'keterangan.required' => 'Keterangan wajib diisi.',
        ];
    }
}
