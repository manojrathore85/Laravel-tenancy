<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function getUpdatesToAttribute($value)
    {
        return $value ? explode(',', $value) : [];
    }

    public function setUpdatesToAttribute($value)
    {
        $this->attributes['update_to'] = implode(',', $value);
    }
    public function subscribers()
    {
        return $this->belongsToMany(User::class, 'issue_subscriptions')->withTimestamps();
    }
}
