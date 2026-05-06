<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class GuestCheckin extends Model
{
    use HasUuids;

    protected $table = "guest_checkin";

    protected $guarded = [""];

    public $incrementing = false;

    protected $keyType = "string";

    public $primaryKey = "id";

    public function guest()
    {
        return $this->belongsTo(Guest::class, "guest_id");
    }
}
