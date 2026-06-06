<?php

namespace App\Support;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class ActivityLogger
{
    private static array $sensitiveKeys = [
        'password',
        'password_confirmation',
        'current_password',
        'token',
        '_token',
    ];

    private static ?array $context = null;

    public static function setContext(string $module, string $action, array $meta = []): void
    {
        self::$context = [
            'module' => $module,
            'action' => $action,
            'meta' => $meta,
        ];
    }

    public static function consumeContext(): ?array
    {
        $ctx = self::$context;
        self::$context = null;
        return $ctx;
    }

    public static function log(
        string $module,
        string $action,
        ?Model $subject = null,
        $before = null,
        $after = null,
        array $meta = []
    ): void {
        $payload = [
            'user_id' => Auth::id(),
            'module' => $module,
            'action' => $action,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? (string) $subject->getKey() : null,
            'method' => Request::method(),
            'url' => self::safeUrl(),
            'ip' => Request::ip(),
            'user_agent' => self::safeUserAgent(),
            'before' => self::encode(self::sanitize($before)),
            'after' => self::encode(self::sanitize($after)),
            'meta' => self::encode(self::sanitize($meta)),
            'logged_at' => now(),
        ];

        self::persist($payload);
    }

    public static function logModelCreated(Model $model, ?array $meta = null, ?string $module = null, ?string $action = null): void
    {
        self::log(
            $module ?: class_basename($model),
            $action ?: 'create',
            $model,
            null,
            $model->toArray(),
            $meta ?: []
        );
    }

    public static function logModelUpdated(Model $model, array $before, array $after, ?array $meta = null, ?string $module = null, ?string $action = null): void
    {
        self::log(
            $module ?: class_basename($model),
            $action ?: 'update',
            $model,
            $before,
            $after,
            $meta ?: []
        );
    }

    public static function logModelDeleted(Model $model, ?array $meta = null, ?string $module = null, ?string $action = null): void
    {
        self::log(
            $module ?: class_basename($model),
            $action ?: 'delete',
            $model,
            $model->toArray(),
            null,
            $meta ?: []
        );
    }

    private static function persist(array $payload): void
    {
        if (method_exists(DB::class, 'afterCommit') && DB::transactionLevel() > 0) {
            DB::afterCommit(function () use ($payload) {
                ActivityLog::create($payload);
            });
            return;
        }

        ActivityLog::create($payload);
    }

    private static function safeUrl(): ?string
    {
        try {
            return Str::limit(Request::fullUrl(), 500, '');
        } catch (\Throwable $e) {
            return null;
        }
    }

    private static function safeUserAgent(): ?string
    {
        try {
            return Str::limit((string) Request::userAgent(), 500, '');
        } catch (\Throwable $e) {
            return null;
        }
    }

    private static function encode($value): ?string
    {
        if ($value === null) return null;
        if ($value === []) return json_encode(new \stdClass());
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    private static function sanitize($value)
    {
        if ($value === null) return null;

        if ($value instanceof Model) {
            return $value->toArray();
        }

        if (is_array($value)) {
            $result = [];
            foreach ($value as $k => $v) {
                if (is_string($k) && in_array($k, self::$sensitiveKeys, true)) {
                    $result[$k] = '[REDACTED]';
                    continue;
                }
                $result[$k] = self::sanitize($v);
            }
            return $result;
        }

        return $value;
    }
}
