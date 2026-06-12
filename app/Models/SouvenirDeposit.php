<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SouvenirDeposit extends Model
{
    use HasUuids;

    protected $table = "souvenir_deposit";

    protected $guarded = [];

    protected $keyType = "string";

    public $incrementing = false;

    public $primaryKey = "id";

    public $timestamps = false;

    public function guest()
    {
        return $this->belongsTo(Guest::class, "guest_id");
    }

    public function guest_public()
    {
        return $this->belongsTo(GuestPublic::class, "guest_public_id");
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, "petugas_id");
    }
}
