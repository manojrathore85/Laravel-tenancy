<?php

namespace App\Models\Tenant;

use App\Models\Tenant\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class IssueType extends BaseModel
{
    use HasFactory;
     protected $guarded = ['id'];
}
