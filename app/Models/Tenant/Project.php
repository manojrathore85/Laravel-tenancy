<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Tenant\BaseModel;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Project extends BaseModel
{
    use HasFactory;

    protected $guarded = ['id'];
    //protected $appends = ['lead_id']; 
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_has_project', 'project_id', 'user_id')
            ->withTimestamps()
            //->withPivot('role_id')
            ->withPivot('is_lead');
    }

    public function getLeadAttribute()
    {
        return $this->users->firstWhere('pivot.is_lead', 1);
    }

    // For setting/changing the lead
    public function setLead($userId)
    {
        $this->users()->updateExistingPivot(           
            $this->users()->pluck('users.id')->toArray(),
            ['is_lead' => 0]
        );

        $this->users()->syncWithoutDetaching([
            $userId => ['is_lead' => 1, 'role_id' => 1],
        ]);
    }
}
