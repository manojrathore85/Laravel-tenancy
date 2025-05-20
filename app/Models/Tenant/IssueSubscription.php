<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Tenant\BaseModel;

class IssueSubscription extends BaseModel
{
    use HasFactory;
    protected $guarded = ['id'];
    
}
