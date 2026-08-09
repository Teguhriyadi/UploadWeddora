<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class APITemaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'kategori' => $this->kategori->nama_kategori,
            'nama' => $this->nama,
            'subtitle' => $this->subtitle,
            'deskripsi' => $this->deskripsi,
            'badge' => $this->badge,
            'gambar' => Storage::disk('s3')->url('souvenir/' . $this->img_bg),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
