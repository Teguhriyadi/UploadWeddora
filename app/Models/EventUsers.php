<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class EventUsers extends Model
{
    use HasUuids;

    protected $table = 'event_users';

    protected $guarded = [''];

    public $incrementing = false;

    protected $keyType = 'string';

    public function event()
    {
        return $this->belongsTo(Event::class, "event_id");
    }

    public function users()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
