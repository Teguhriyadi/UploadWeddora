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

    public function inject()
    {
        return $this->belongsTo(User::class, "inject_by", "id");
    }

    public function scopeFilter($query, $request)
    {
        $kehadiran = $request->kehadiran;

        if ($kehadiran === 'null') {

            $query->whereNull('kehadiran');
        } elseif (in_array($kehadiran, ['0', '1', '2'])) {

            $query->where('kehadiran', $kehadiran);
        }

        if (!empty($request->keterangan)) {

            $query->where(
                'keterangan',
                $request->keterangan
            );
        }

        if (in_array($request->status, ['0', '1'])) {

            $query->where(
                'status_kehadiran',
                $request->status
            );
        }

        return $query;
    }
}
