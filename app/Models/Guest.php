<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasUuids;

    protected $table = "guest";

    protected $guarded = [""];

    public $incrementing = false;

    protected $keyType = "string";

    public $primaryKey = "id";

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, "kategori_id");
    }
}
