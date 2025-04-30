<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tenant\Issue;

class Comment extends Model
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
