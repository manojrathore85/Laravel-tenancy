<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Requests\Api\IssueRequest;
use App\Models\Tenant\Issue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\IssueUpdatedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Tenant\ActivityLog;
class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       // print_r($request->all());
        try {
            //$issues = Issue::all();
           // \DB::enableQueryLog();
            $issues = DB::table('issues')
                ->join('projects', 'issues.project_id', '=', 'projects.id')
                ->join('users', 'issues.assigned_to', '=', 'users.id')
                ->join('users as created_by', 'issues.created_by', '=', 'created_by.id')
                ->leftJoin('issue_subscriptions', function ($join) {
                    $join->on('issues.id', '=', 'issue_subscriptions.issue_id')
                         ->where('issue_subscriptions.user_id', '=', auth()->id());
                })
                ->select(
                    'issues.id',
                    'issues.issue_type',
                    'issues.severity',
                    'issues.summery',
                    'issues.description',
                    'issues.status',
                    'issues.attachment',
                    'issues.created_at',
                    'issues.updated_at',
                    'projects.id as project_id',
                    'projects.name as project_name',
                    'users.name as assigned_user_name',
                    'created_by.name as created_user_name',
                    DB::raw('CASE WHEN issue_subscriptions.user_id IS NOT NULL THEN true ELSE false END as is_subscribed')
                )
              //  ->whereIn('projects.id', auth()->user()->projects()->pluck('project_id')->toArray())
                ->where(function ($query) {
                    $query->whereIn('projects.id', auth()->user()->projects()->pluck('project_id')->toArray())
                        ->orWhere('issues.created_by', auth()->id())
                        ->orWhere('issues.assigned_to', auth()->id());
                })
                ->when($request->filled('status') && $request->status !== 'all', function ($query) use ($request) {
                    $query->where('issues.status', $request->status);
                })
                ->when($request->filled('assigned_to') && $request->assigned_to !== 'all', function ($query) use ($request) {
                    $query->where('issues.assigned_to', $request->assigned_to);
                })
                ->when($request->filled('issue_type') && $request->issue_type !== 'all', function ($query) use ($request) {
                    $query->where('issues.issue_type', $request->issue_type);
                })
                ->when($request->filled('severity') && $request->severity !== 'all', function ($query) use ($request) {
                    $query->where('issues.severity', $request->severity);
                })
                ->when($request->filled('project_id') && $request->project_id !== 'all', function ($query) use ($request) {
                    $query->where('issues.project_id', $request->project_id);
                })
                ->when($request->filled('created_by') && $request->created_by !== 'all', function ($query) use ($request) {
                    $query->where('issues.created_by', $request->created_by);
                })
                ->when($request->filled('tag') && $request->tag == 'important', function ($query) use ($request) {
                    $query->where(function ($q) {
                        $q->where('issues.severity', 'major')
                        ->orWhere('issues.severity', 'blocker')
                        ->orWhere('issues.severity', 'critical');
                    });                    
                })          
                ->when($request->filled('order_by') && !empty($request->order_by), function ($query) use ($request) {
                   
                    $allowedSortFields = ['id', 'status', 'issue_type', 'created_at', 'updated_at','severity']; // whitelist
                    $orderby = in_array($request->order_by, $allowedSortFields) ? $request->order_by : 'created_at';
                    $order = in_array(strtolower($request->order), ['asc', 'desc']) ? $request->order : 'asc';
                   
                    $query->orderBy('issues.' . $orderby, $order);
                })
                ->when($request->filled('search'), function ($query) use ($request) {
                    $query->where(function ($q) use ($request) {
                        $q->where('issues.summery', 'like', '%' . $request->search . '%')
                        ->orWhere('issues.description', 'like', '%' . $request->search . '%');
                    });
                })
                ->get()
                 ->map(function ($issue) {
                    $timezone = auth()->user()->timezone ?? 'UTC';
                    $issue->created_at = \Carbon\Carbon::parse($issue->created_at)
                        ->timezone($timezone)
                        ->format('Y-m-d H:i:s T');
                    $issue->updated_at = \Carbon\Carbon::parse($issue->updated_at)
                        ->timezone($timezone)
                        ->format('Y-m-d H:i:s T');
                    return $issue;
                });

              //  \Log::info(\DB::getQueryLog());
            return response()->json($issues, 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IssueRequest $request)
    {
        try {
            $data = $request->all();
            //print_r($request->all());
            // Handle file upload
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $path = $file->store('issues', 'public');

                $data['attachment'] = $path;
            }

            $data['created_by'] = auth()->user()->id;

            Issue::create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Issue created successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        try {
            $issue = Issue::findOrfail($id);
            return response()->json($issue, 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Issue $issue)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IssueRequest $request, string $id)
    {
        try {
            $data = $request->all();
            //return response()->json($request->all());
            $issue = Issue::find($id);
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $path = $file->store('issues', 'public');

                $data['attachment'] = $path;
            }
            $data['updated_by'] = auth()->user()->id;
            $issue->update($data);
          
            // Notify subscribers
            foreach ($issue->subscribers as $user) {
                //if ($user->id !== auth()->id()) {
                    \Log::info("email sending to user id ".$user->id);
                    $user->notify(new IssueUpdatedNotification($issue));
                //}
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Issue updated successfully',
            ], 200);
        } catch (\Throwable $th) {
            \Log::error($th->getMessage());
             return response()->json([
                'status' => 'success',
                'message' => 'Issue updated successfully',
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $ids)
    {
        if (empty($ids)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Ids not found',
            ], 400);
        }
        try {
            $attachachments = Issue::select('attachment')->whereIn('id', explode(',', $ids))->get();
            foreach ($attachachments as $attachment) {
                if ($attachment->attachment && Storage::disk('public')->exists($attachment->attachment)) {
                    Storage::disk('public')->delete($attachment->attachment);
                }
            }
            $idsArray = explode(',', $ids);
            Issue::whereIn('id', $idsArray)->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Issue deleted successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 500);
        }
    }
    public function removeAttachment(string $id)
    {
        try {
            $issue = Issue::find($id);
            if ($issue->attachment) {
                //echo "removing file";
                // Delete the file from storage
                //Storage::disk('public')->delete('attachments/' . $issue->attachment);
                //print_r(Storage::disk('public')->delete('tenant' . tenant('id') . '/app/public/attachments/' . $issue->attachment));
                if ($issue->attachment && Storage::disk('public')->exists($issue->attachment)) {
                    //Log::info('removing file');
                    // echo $issue->attachment;
                    Storage::disk('public')->delete($issue->attachment);
                }
                //echo("deleted");

                // Set the DB column to null
                $issue->attachment = null;
                $issue->save();
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Attachment removed successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 500);
        }
    }
    public function subscribe(Issue $issue)
    {
        try {
            $issue->subscribers()->syncWithoutDetaching([auth()->id()]);
            return response()->json([ 'message' => 'Subscribed'], 200);
        } catch (\Throwable $th) {
            return response()->json([ 'message' => $th], 500);
        }
    }

    public function unsubscribe(Issue $issue)
    {
       try {
        $issue->subscribers()->detach(auth()->id());
        return response()->json(['message' => 'Unsubscribed'], 200);
       } catch (\Throwable $th) {
        return response()->json([ 'message' => $th], 500);
       }
  
    }
    public function getLogs(Issue $issue){
            
            $logs = ActivityLog::query()
            ->where('subject_type', Issue::class)
            ->where('log_name', 'issue')
            ->where('subject_id', $issue->id)
            ->leftJoin('users', 'activity_log.causer_id', '=', 'users.id')
            ->select(
                'activity_log.*',
                'users.name as causer_name',
                'users.email as causer_email'
            )
            ->latest()
            ->get();
        return response()->json($logs,200);        
    }

}
