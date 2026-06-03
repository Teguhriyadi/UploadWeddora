<?php

namespace App\Http\Requests\TitipKado;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'guest_id' => 'nullable|exists:guest,id',
            'nama_tamu' => 'nullable|string|max:255',
            'nama_kado' => 'required',
            'qty' => 'required|integer|min:1',
            'keterangan' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'guest_id.exists' => 'Tamu yang dipilih tidak valid.',
            'nama_tamu.string' => 'Nama tamu harus berupa teks.',
            'nama_tamu.max' => 'Nama tamu maksimal 255 karakter.',
            'nama_kado.required' => 'Nama Kategori Wajib Diisi',
            'qty.required' => 'QTY Wajib Diisi',
            'qty.integer' => 'QTY harus berupa angka bulat.',
            'qty.min' => 'QTY minimal 1.',
            'keterangan.required' => 'Keterangan Wajib Diisi'
        ];
    }
}
