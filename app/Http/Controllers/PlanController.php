<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlanRequest;
use App\Models\Plan;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $plan = Plan::all();
            return response()->json($plan,200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PlanRequest $request)
    {
        try {
            Plan::create($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Plan created successfuly',
            ], 200);
        }catch (\Throwable $th) {
            return response()->json([
                'status' => 'fail',
                'message' => $th->getMessage(), 
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $plan = Plan::findOrfail($id);
            return response()->json($plan,200);
        }catch (\Throwable $th) {
            return response()->json($th->getMessage(),500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PlanRequest $request, string $id)
    {
        try {
            $project = Plan::find($id);
            $project->update($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Plan Updated successfuly',
            ], 200);
        }catch (\Throwable $th) {
            return response()->json([
                'status' => 'fail',
                'message' => $th->getMessage(), 
            ],500);
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

            Plan::destroy($idsArray);
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
}
