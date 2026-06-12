<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TitipKehadiran extends Model
{
    use HasUuids;

    protected $table = "titip_kehadiran";

    protected $guarded = [""];

    public $primaryKey = "id";

    protected $keyType = "string";

    public $timestamps = false;

    public function wakil_tamu()
    {
        return $this->belongsTo(Guest::class, "wakil_id", "id");
    }

    public function wakil_tamu_luar()
    {
        return $this->belongsTo(GuestPublic::class, "wakil_guest_public_id", "id");
    }

    public function tamu_berhalangan()
    {
        return $this->belongsTo(Guest::class, "guest_id", "id");
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, "petugas_id", "id");
    }
}
