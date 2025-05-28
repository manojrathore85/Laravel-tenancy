<?php

namespace App\Models\Tenant;

use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Activity
{
    public function getCreatedAtAttribute($value)
    {
        $timezone = auth()->user()->timezone ?? 'UTC';
        return \Carbon\Carbon::parse($value)->timezone($timezone)->format(config('app.date_display_format'));
    }

    public function getUpdatedAtAttribute($value)
    {
        $timezone = auth()->user()->timezone ?? 'UTC';
        return \Carbon\Carbon::parse($value)->timezone($timezone)->format(config('app.date_display_format'));
    }
    public function user()
    {
        return $this->belongsTo(\App\Models\Tenant\User::class, 'causer_id');
    }
    public function causer(): MorphTo
    {
        return $this->morphTo();
    }
}
