<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;

use App\Models\Menu;
use App\Http\Requests\Api\MenuRequest;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $menus = Menu::with('parent:id,name')->get();
            return response()->json($menus, 200);
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
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(MenuRequest $request)
    {
        try {
          // return( json_encode($request->all()));
            $menu = Menu::create($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Menu created successfully',
                'menu' => $menu,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'fail',
                'message' => $th->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $menu = Menu::find($id);
            return response()->json($menu, 200);
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
    public function update(MenuRequest $request, string $id)
    {
        try {
            $user = Menu::find($id);
            $user->update($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'User Updated successfully',
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

            Menu::destroy($idsArray);
            return response()->json([
                'status' => 'success',
                'message' => 'User Updated successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 500);
        }
    }
    public function menuPermission($roleId)
    {
        try {
            $menuPermissions = Menu::with([
                'roleMenuPermissions' => function ($query) use ($roleId) {
                    $query->where('role_id', $roleId);
                }
            ])->get();
            //dd($menuPermissions);
            // Transform the data to ensure default values when roleMenuPermissions is null
            $menuPermissions = $menuPermissions->map(function ($menu) use ($roleId) {
                return [
                    "id" => $menu->id,
                    "name" => $menu->name,
                    "route" => $menu->route,
                    "icon" => $menu->icon,
                    "parent_id" =>  $menu->parent_id,
                    "sort_order" =>  $menu->sort_order,
                    "created_at" => $menu->created_at,
                    "updated_at" => $menu->updated_at,
                    "component" => $menu->component,
                    "role_menu_permissions" => $menu->roleMenuPermissions ??  [

                        "role_id" => $roleId,
                        "menu_id" => $menu->id,
                        "can_add" => false,
                        "can_edit" => false,
                        "can_delete" => false,
                        "can_view" => false,
                        "created_at" => false,
                        "updated_at" =>  false,
                    ]
                ];
            });
            return response()->json($menuPermissions, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'fail',
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function getMenuByRole($roleId)
    {
        try {
            $menus = Menu::with(['parent:id,name', 'roleMenuPermissions'])
                ->whereHas('roleMenuPermissions', function ($query) use ($roleId) {
                    $query->where(function ($q) {
                        $q->where('can_add', 1)
                        ->orWhere('can_edit', 1)
                        ->orWhere('can_view', 1)
                        ->orWhere('can_delete', 1);
                       
                    })
                    ->where('role_id', $roleId);
                    })               
                ->get();
            
            return response()->json($menus, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
