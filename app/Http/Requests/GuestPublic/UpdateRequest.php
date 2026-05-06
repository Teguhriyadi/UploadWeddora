<?php

namespace App\Http\Requests\GuestPublic;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama' => 'required',
            'nomor_handphone' => [
                'nullable',
                'regex:/^(\+62|62|0)[0-9]{8,13}$/'
            ],
            'pekerjaan' => [
                'nullable',
                'regex:/^[A-Za-z\s]+$/'
            ],
            'alamat' => 'nullable'
        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Nama Tamu Wajib Diisi',
            'nomor_handphone.regex' => 'No. Handphone harus berupa nomor yang valid (contoh: 08123456789 atau +628123456789)',
            'pekerjaan.regex' => 'Pekerjaan hanya boleh berupa huruf'
        ];
    }
}
