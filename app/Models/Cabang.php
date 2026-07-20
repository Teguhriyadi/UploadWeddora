<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasUuids;

    protected $table = 'cabang';

    protected $guarded = [''];

    public $incrementing = false;

    protected $keyType = 'string';
}
