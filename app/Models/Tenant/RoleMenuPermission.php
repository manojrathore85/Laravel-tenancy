<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Role;
use App\Models\Menu;

class RoleMenuPermission extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function role(){
        $this->belongsTo(Role::class);
    }
    public function menu(){
        $this->belongsTo(Menu::class);
    }

}
