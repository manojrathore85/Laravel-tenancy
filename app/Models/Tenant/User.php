<?php

namespace App\Models\Tenant;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    //protected $guard_name = ['sanctum']; //'web',
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'gender',
        'password',
        'phone',
        'status',
        'profile_image',
        'is_super_admin',
        'assigned_projects',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    protected function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
    protected $appends = ['profile_image_url'];
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'user_has_project', 'user_id', 'project_id')
                    ->withTimestamps();
    }
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return url("tenant".tenant('id')."/".$this->profile_image);
        }
        // fallback image or null
        return url('/images/default-avatar.png'); 
    }
    public function getRoleForProject(string $projectId) {
        return $this->hasMany(UserHasProject::class, 'user_id', 'id')->where('project_id', $projectId)->first();
    }
    public function getAssignedProjectsAttribute()
    {
        if ($this->is_super_admin) {
            return Project::all();
        }
    
        return $this->projects;
    }  
    public function subscribedIssues()
    {
        return $this->belongsToMany(Issue::class, 'issue_subscriptions')->withTimestamps();
    }
}
