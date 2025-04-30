<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Requests\Api\CommentRequest;
use App\Models\Tenant\Comment;
use App\Models\Tenant\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

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
            $comments = Comment::with('commentBy')->with('updateBy')
            ->where('issue_id', $issue->id)
            ->get();
            return response()->json($comments, 200);
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
            Comment::create($data);
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
    public function update(CommentRequest $request, Comment $comment)
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
            $comment= Comment::findOrFail($comment->id);
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
