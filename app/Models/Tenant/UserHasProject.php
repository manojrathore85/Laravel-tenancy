<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Project;
use App\Models\Tenant\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class UserHasProject extends Model
{
    use HasFactory;
    protected $table = 'user_has_project';

    protected $guarded = ['id'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

}
