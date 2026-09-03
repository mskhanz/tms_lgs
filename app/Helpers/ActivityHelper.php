<?php

use App\Models\ActivityLog;
use App\Support\SchemaCache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

if (!function_exists('activity')) {
    function activity($log = null)
    {
        $activityLogger = app(ActivityLogger::class);

        if (!is_null($log)) {
            return $activityLogger->log($log);
        }

        return $activityLogger;
    }
}

class ActivityLogger
{
    protected $performedOn;
    protected $causedBy;
    protected $properties = [];
    protected $logName;

    public function performedOn($model)
    {
        $this->performedOn = $model;
        return $this;
    }

    public function causedBy($user)
    {
        $this->causedBy = $user;
        return $this;
    }

    public function withProperties(array $properties)
    {
        $this->properties = $properties;
        return $this;
    }

    public function useLog($logName)
    {
        $this->logName = $logName;
        return $this;
    }

    public function log($description)
    {
        try {
            if (! SchemaCache::hasTable('activity_logs')) {
                $this->reset();
                return $this;
            }

            $user = $this->causedBy ?: Auth::user();
            $request = request();

            ActivityLog::create([
                'log_name' => $this->logName ?: 'system',
                'description' => $description,
                'user_id' => $user ? $user->id : null,
                'subject_type' => $this->performedOn ? get_class($this->performedOn) : null,
                'subject_id' => $this->performedOn?->id,
                'causer_type' => $user ? get_class($user) : null,
                'causer_id' => $user ? $user->id : null,
                'properties' => $this->properties,
                'ip_address' => $request ? $request->ip() : null,
                'user_agent' => $request ? substr((string) $request->userAgent(), 0, 255) : null,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Activity log failed: '.$e->getMessage());
        }

        $this->reset();

        return $this;
    }

    private function reset(): void
    {
        $this->performedOn = null;
        $this->causedBy = null;
        $this->properties = [];
        $this->logName = null;
    }
}
