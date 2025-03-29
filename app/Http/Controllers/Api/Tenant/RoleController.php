<?php

namespace App\Http\Controllers\Api\Tenant;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Tenant\RoleMenuPermission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $menu = Role::All();
            return response()->json($menu, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'=>'error',
                'message'=>$th->getMessage(),
            ],500);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function updatePermissions(Request $request, $roleId)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'can_add' => 'required|boolean',
            'can_edit' => 'required|boolean',
            'can_delete' => 'required|boolean',
            'can_view' => 'required|boolean',
        ]);

        // Find the record in the role_menu_permissions table
        $permission = RoleMenuPermission::where('role_id', $roleId)
            ->where('menu_id', $request->menu_id)
            ->first();

        if (!$permission) {
            // Create new permission if it doesn't exist
            $permission = new RoleMenuPermission();
            $permission->role_id = $roleId;
            $permission->menu_id = $request->menu_id;
        }

        // Update permissions
        $permission->can_add = $request->can_add;
        $permission->can_edit = $request->can_edit;
        $permission->can_delete = $request->can_delete;
        $permission->can_view = $request->can_view;

        $permission->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Permission updated successfully',
            'data' => $permission,
        ]);
    }

}
