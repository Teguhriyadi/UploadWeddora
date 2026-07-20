<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'cabang_id' => 'required',
            'nama_cpp' => 'required',
            'nama_cpw' => 'required',
            'nama_event' => 'required',
            'tanggal' => 'required',
            'lokasi' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'cabang_id.required' => 'Nama Cabang Wajib Diisi',
            'nama_cpp.required' => 'Nama CPP Wajib Diisi',
            'nama_cpw.required' => 'Nama CPW Wajib Diisi',
            'nama_event.required' => 'Nama Event Wajib Diisi',
            'tanggal.required' => 'Tanggal Wajib Diisi',
            'lokasi.required' => 'Lokasi Wajib Diisi'
        ];
    }
}
