<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class LPTema extends Model
{
    use HasUuids;

    protected $table = "lp_tema";

    protected $guarded = [""];

    public $incrementing = false;

    protected $keyType = "string";

    public $primaryKey = "id";

    public function kategori()
    {
        return $this->belongsTo(LPKategori::class, "lp_kategori_id");
    }
}
