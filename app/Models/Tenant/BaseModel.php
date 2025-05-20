<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HasFactory;

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
}
