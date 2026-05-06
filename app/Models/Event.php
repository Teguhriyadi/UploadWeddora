<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasUuids;

    protected $table = "event";

    protected $guarded = [""];

    public $incrementing = false;

    protected $keyType = "string";

    public $primaryKey = "id";
}
