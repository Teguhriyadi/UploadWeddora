<?php

namespace App\Http\Requests\Guest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama_tamu' => 'required',
            'nama_undangan' => 'required',
            'relasi' => 'required',
            'jenis_undangan' => 'required',
            'keterangan' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'nama_tamu.required' => 'Nama Tamu Wajib Diisi',
            'nama_undangan.required' => 'Nama Undangan Wajib Diisi',
            'relasi.required' => 'Relasi Wajib Diisi',
            'jenis_undangan.required' => 'Jenis Undangan Wajib Diisi',
            'keterangan.required' => 'Keterangan Wajib Diisi'
        ];
    }
}
