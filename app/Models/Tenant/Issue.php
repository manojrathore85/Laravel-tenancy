<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;  
use App\Models\Tenant\BaseModel;
use App\Models\Tenant\ActivityLog;

class Issue extends BaseModel
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = ['id'];

    protected static $logName = 'issue';
    protected static $logAttributes = ['issue_type', 'severity','project_id','description', 'status', 'assigned_to', 'summery', 'attachment'];
    protected static $logOnlyDirty = true; // only log changed fields

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function subscribers()
    {
        return $this->belongsToMany(User::class, 'issue_subscriptions')->withTimestamps();
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['issue_type', 'severity','project_id','description', 'status', 'assigned_to', 'summery', 'attachment'])
            ->useLogName('issue')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Issue has been {$eventName}");
    }
    public function tapActivity(ActivityLog $activity, string $eventName)
{
    $properties = $activity->properties->toArray();

    if (isset($properties['attributes']['assigned_to'])) {
        $assignedUser = \App\Models\Tenant\User::find($properties['attributes']['assigned_to']);
        $properties['attributes']['assigned_to_name'] = $assignedUser?->name;
    }

    if (isset($properties['old']['assigned_to'])) {
        $oldAssignedUser = \App\Models\Tenant\User::find($properties['old']['assigned_to']);
        $properties['old']['assigned_to_name'] = $oldAssignedUser?->name;
    }


    // Re-assign modified properties
    $activity->properties = collect($properties);
}


}
