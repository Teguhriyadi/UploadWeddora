<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class GuestPublic extends Model
{
    use HasUuids;

    protected $table = "guest_public";

    protected $guarded = [""];

    public $incrementing = false;

    protected $keyType = "string";

    public $primaryKey = "id";
}
