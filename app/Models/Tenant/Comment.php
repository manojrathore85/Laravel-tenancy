<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Tenant\Issue;
use App\Models\Tenant\BaseModel;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
class Comment extends BaseModel
{
    use HasFactory;
    use LogsActivity;
    protected $guarded = ['id'];

 

    protected static $logName = 'comment';

    protected static $logAttributes = ['comment', 'issue_id'];
    protected static $logOnlyDirty = true;


    public function issue()
    {
        return $this->belongsTo(Issue::class);
    }
    public function commentBy()
    {
        return $this->belongsTo(User::class, 'comment_by');
    }
    public function updateBy()
    {
        return $this->belongsTo(User::class, 'update_by');
    }

    public function activityLog(){
        return $this->hasMany(ActivityLog::class, 'subject_id', 'id',)->where('log_name', 'comment')->with('causer');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['description', 'issue_id', 'attachment'])
            ->useLogName('comment')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Comment has been {$eventName}");
    }
}
