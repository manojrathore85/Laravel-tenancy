<?php

namespace App\Http\Controllers\Api\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\IssueStatus;
use App\Models\Tenant\IssueType;
use App\Models\Tenant\IssueSeverity;

class DropDownController extends Controller
{
    
    public function issueStatusOptions() {
        $options = IssueStatus::all();
        return response()->json($options, 200);
    }
    
    public function issueTypeOptions() {
        $options = IssueType::all();
        return response()->json($options, 200);
    }
    public function issueSeverityOptions() {
        $options = IssueSeverity::all();
        return response()->json($options, 200);
    }
}
