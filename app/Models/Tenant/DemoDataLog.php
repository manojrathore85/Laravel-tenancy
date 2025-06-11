<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class DemoDataLog extends Model
{
    use HasFactory;

    protected $table = 'demo_data_log';

    protected $guarded = ['id'];
}
