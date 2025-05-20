<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Tenant\BaseModel;

class Issue extends BaseModel
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

    public function subscribers()
    {
        return $this->belongsToMany(User::class, 'issue_subscriptions')->withTimestamps();
    }
}
