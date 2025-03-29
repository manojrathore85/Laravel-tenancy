<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Contracts\Permission;
use app\Http\Models\Tenant\roleMenuPermissions;
use App\Models\Tenant\RoleMenuPermission;

class Menu extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function permission()
    {
        $this->belongsTo(Permission::class);
    }
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }
    public function roleMenuPermissions(){
        return $this->hasOne(RoleMenuPermission::class, 'menu_id', 'id');
    }
}
