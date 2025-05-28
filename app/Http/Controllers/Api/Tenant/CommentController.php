<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Requests\Api\CommentRequest;
use App\Models\Tenant\Comment;
use App\Models\Tenant\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Notifications\IssueCommentedNotification;
use App\Models\Tenant\ActivityLog;
use Carbon\Carbon;
class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function getIssueComments(Issue $issue)
    {
    
        try {
            $comments = Comment::with('commentBy')->with('updateBy')->with('ActivityLog')
            ->where('issue_id', $issue->id)
            ->latest()
            ->get();
            
            $logs = ActivityLog::with('user')
                ->where('subject_type', Issue::class)
                ->where('log_name', 'issue')
                ->where('subject_id', $issue->id)
                ->latest()
                ->get();
             

            $comments->transform(function ($item) {
                $item->type = 'comment';
                $item->sort_timestamp = Carbon::createFromFormat('d-m-Y H:i:s T', $item->updated_at);
                return $item;
            });

            $logs->transform(function ($item) {
                $item->type = 'log';
                $item->sort_timestamp = Carbon::createFromFormat('d-m-Y H:i:s T', $item->updated_at ?? $item->created_at);
                return $item;
            });

            // Step 3: Merge and sort by `updated_at`
           $merged = $comments->merge($logs)
    ->sortBy('sort_timestamp')
    ->values();


            
            
            return response()->json($merged, 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 500);
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
    public function store(CommentRequest $request)
    {
        try {
            $data = $request->all();
            if($request->hasFile('attachment')){
                $file = $request->file('attachment');
                $path = $file->store('comments','public');
                $data['attachment'] = $path;
            }
            $data['comment_by'] = auth()->user()->id;      
            $issue = Issue::find($data['issue_id']);
           //check if $data['status'] is set and not empty the only 
            if (isset($data['status']) && !empty($data['status'])) {
                $issue->status = $data['status'];
            }
            $issue->save();
            $comment = Comment::create($data);      
            

            // Notify subscribers
            foreach ($issue->subscribers as $user) {
                if ($user->id !== auth()->id()) {
                    $user->notify(new IssueCommentedNotification($issue, $comment));
                }
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Comment added successfully',
            ],200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 500);        
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        try {
            $comment = Comment::findOrFail($comment->id)->with('user')->first();
            return response()->json($comment, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ],500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentRequest $request, Comment $comments)
    {
        try {
            $data = $request->all();
            if($request->hasFile('attachment')){
                $file =$request->file('attachment');
                $path = $file->store('comments', 'public');
                $data['attachment'] = $path;
            }
            //$data['comment_by'] = auth()->user()->id;
            $data['updated_by'] = auth()->user()->id;
            $comment= Comment::findOrFail($comments->id);
            $comment->update($data);
            return response()->json([
                'status' => 'success',
                'message' => 'Comment updated successfully',
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ],500);
        }        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $ids)
    {
        if(empty($ids)){
            return response()->json([
                'status' => 'failed',
                'message' => 'Ids not found',
            ], 400);
        }
        try {


            $idsArray= explode(",", $ids);
            $attachments= Comment::find($idsArray)->pluck('attachment');
            foreach($attachments as $attachment){
                if($attachment->attachment && Storage::disk('public')->exists($$attachment->attachment)){
                    Storage::disk('public')->delete($attachment->attachment);
                }
            }
            Comment::destroy($idsArray);
            return response()->json([
                'status' => 'success',
                'message' => 'Comment deleted successfully',
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ],500);
        }
    }
    public function removeAttachement(string  $id){
        try {
            $comment = Comment::findOrFail($id);
            if($comment->attachment && Storage::disk('public')->exists($comment->attachment)){
                Storage::disk('public')->delete($comment->attachment);
            }
            $comment->attachment = null;
            $comment->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Attachment removed successfully',
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ],500);
        }
    }

}
