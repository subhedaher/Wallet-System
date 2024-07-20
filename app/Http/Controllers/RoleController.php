<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Role::class, 'role');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::withCount('permissions')->get();
        return view('cms.roles.index', ['roles' => $roles]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cms.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator($request->all(), [
            'guard' => 'required|string|in:admin,shippingPoint,user-api',
            'name' => 'required|string'
        ]);

        if (!$validator->fails()) {
            $role = Role::create([
                'name' => $request->input('name'),
                'guard_name' => $request->input('guard')
            ]);
            return response()->json([
                'status' => $role ? true : false,
                'message' => $role ? "Create Role Successfully" : "Create Role Failed!"
            ], $role ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $permissions = Permission::where('guard_name', '=', $role->guard_name)->get();
        $rolePermissions = $role->permissions;

        if (count($rolePermissions)) {
            foreach ($rolePermissions as $userPermissions) {
                foreach ($permissions as $permission) {
                    if ($permission->id === $userPermissions->id) {
                        $permission->setAttribute('assigned', true);
                    }
                }
            }
        }

        return view('cms.roles.role-permissions', ['role' => $role, 'permissions' => $permissions]);
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function updateRolePermission(Request $request)
    {
        $validator = Validator($request->all(), [
            'role_id' => 'required|numeric|exists:roles,id',
            'permission_id' => 'required|numeric|exists:permissions,id'
        ]);
        if (!$validator->fails()) {
            $permission = Permission::findOrFail($request->input('permission_id'));
            $role = Role::findOrFail($request->input('role_id'));

            $role->hasPermissionTo($permission)
                ? $role->revokePermissionTo($permission)
                : $role->givePermissionTo($permission);

            return response()->json([
                'status' => true,
                'message' => 'permission Updated Successfully'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function edit(Role $role)
    {

        return view('cms.roles.edit', ['role' => $role]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validator = Validator($request->all(), [
            'guard' => 'required|string|in:admin,shippingPoint',
            'name' => 'required|string|unique:roles,name,' . $role->id
        ]);
        if (!$validator->fails()) {
            $role->guard_name = $request->input('guard');
            $role->name = $request->input('name');
            $isUpdated = $role->save();
            return response()->json([
                'status' => $isUpdated,
                'message' => $isUpdated ? "Role Updated Successfully" : "Role Updated Failed!"
            ], $isUpdated ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $isDeleted = Role::destroy($role->id);
        return response()->json([
            'status' => $isDeleted,
            'message' => $isDeleted ? "Role Deleted Successfully" : "Role Deleted Faild!"
        ], $isDeleted ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }
}