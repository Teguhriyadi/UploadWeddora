<?php

namespace App\Observers;

use App\Support\ActivityLogger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class ActivityObserver
{
    public function created(Model $model): void
    {
        $ctx = ActivityLogger::consumeContext();
        ActivityLogger::logModelCreated($model, $ctx['meta'] ?? [], $ctx['module'] ?? null, $ctx['action'] ?? null);
    }

    public function updated(Model $model): void
    {
        $ctx = ActivityLogger::consumeContext();

        $changes = $model->getChanges();
        unset($changes['updated_at'], $changes['created_at']);

        if (empty($changes)) return;

        $keys = array_keys($changes);
        $before = Arr::only($model->getOriginal(), $keys);
        $after = Arr::only($model->getAttributes(), $keys);

        ActivityLogger::logModelUpdated(
            $model,
            $before,
            $after,
            $ctx['meta'] ?? [],
            $ctx['module'] ?? null,
            $ctx['action'] ?? null
        );
    }

    public function deleted(Model $model): void
    {
        $ctx = ActivityLogger::consumeContext();
        ActivityLogger::logModelDeleted($model, $ctx['meta'] ?? [], $ctx['module'] ?? null, $ctx['action'] ?? null);
    }
}
