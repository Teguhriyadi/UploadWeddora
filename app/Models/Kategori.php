<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasUuids;

    protected $table = "kategori";

    protected $guarded = [""];

    public $incrementing = false;

    protected $keyType = "string";

    public $primaryKey = "id";
}
