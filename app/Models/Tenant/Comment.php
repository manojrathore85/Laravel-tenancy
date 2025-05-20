<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Tenant\Issue;
use App\Models\Tenant\BaseModel;

class Comment extends BaseModel
{
    use HasFactory;
    protected $guarded = ['id'];

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
}
