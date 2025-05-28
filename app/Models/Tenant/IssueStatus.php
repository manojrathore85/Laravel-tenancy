<?php

namespace App\Models\Tenant;

use App\Models\Tenant\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class IssueStatus extends BaseModel
{
    use HasFactory;
    protected $table = 'issue_status';
    protected $guarded = ['id'];

    
}

