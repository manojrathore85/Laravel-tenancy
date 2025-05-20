<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Tenant\BaseModel;

class Project extends BaseModel
{
    use HasFactory;

    protected $guarded = ['id'];
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_has_project', 'project_id', 'user_id')
            ->withTimestamps();
    }
}
