<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Project;
use Illuminate\Http\Request;
use App\Http\Requests\Api\ProjectRequest;
use App\Models\Tenant\UserHasProject;
use App\Models\Tenant\User;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {   
            $user = auth()->user();

            if ($user->is_super_admin === 1) {
                return Project::all();
            }

            $projects = User::find(auth()->user()->id)->projects()->get();
            return response()->json($projects, 200);
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
    public function store(ProjectRequest $request)
    {
        try {
            $project = Project::create($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Project created successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $project = Project::find($id);
            return response()->json($project, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'fail',
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectRequest $request, string $id)
    {
        try {
            $project = Project::find($id);
            $project->update($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Project Updated successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 500);
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
            $idsArray = explode(',', $ids);

            Project::destroy($idsArray);
            return response()->json([
                'status' => 'success',
                'message' => 'Project Updated successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 500);
        }
    }
    public function assignUsers(Request $request){
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*.user_id' => 'required|integer|exists:users,id',
            'user_ids.*.role_id' => 'nullable|integer',
            'project_id' => 'required|integer|exists:projects,id',
        ]);
    
        try {
            $project = Project::findOrFail($request->project_id);
    
            $syncData = [];
    
            foreach ($request->user_ids as $user) {
                $syncData[$user['user_id']] = [
                    'role_id' => $user['role_id'],
                    'created_by' => auth()->id(),     // optional: use your logic
                    'updated_by' => auth()->id(),
                ];
            }
    
            $project->users()->sync($syncData);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Users assigned successfully.',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 500);
        }
    }
    public function getAssignedUsers(string $projectId) {
        try {
            $assignedUsers =UserHasProject::select('user_id', 'role_id')->where('project_id', $projectId)->get();
          
            return response()->json([
                'status' => 'success',
                'data' => $assignedUsers,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
