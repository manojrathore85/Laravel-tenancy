<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_has_project', 'project_id', 'user_id')
                    ->withTimestamps();
    }
}
