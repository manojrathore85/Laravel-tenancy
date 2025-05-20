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

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            //$issues = Issue::all();
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
                ->whereIn('projects.id', auth()->user()->projects()->pluck('project_id')->toArray())
                ->orWhere('issues.created_by', auth()->user()->id)
                ->orWhere('issues.assigned_to', auth()->user()->id)
                ->orderBy('issues.created_at', 'desc')

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
            //throw $th;
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
}
