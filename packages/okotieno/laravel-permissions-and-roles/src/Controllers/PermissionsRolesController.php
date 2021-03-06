<?php

namespace Okotieno\PermissionsAndRoles\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Okotieno\PermissionsAndRoles\Models\Role;
use Okotieno\PermissionsAndRoles\Requests\UpdateUserRoleRequest;
use Spatie\Permission\Models\Permission;

class PermissionsRolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index( Request $request)
    {
        $response = [];
        if ($request->staff !== null) {
            return Role::staffs()->get()->map(function($staff) {
                return ['id' => $staff['id'], 'name' => $staff['name']];
            });
        }

        foreach (Role::all() as $role) {
            $permissions = [];
            foreach ($role->permissions as $permission) {
                $permissions[] = [
                    'id' => $permission['id'],
                    'name' => $permission['name'],
                ];
            }
            $response[] = [
                'id' => $role['id'],
                'name' => $role['name'],
                'permissions' => $permissions
            ];
        }
        return response()->json($response);
    }

    public function show(Role $role, Request $request)
    {
        if ($request->boolean('allPermissions') === true) {
            $permissions = [];
            foreach (Permission::all() as $permission) {
                if ($role->hasPermissionTo($permission->name)) {
                    $permissions[] = [
                        'id' => $permission['id'],
                        'name' => $permission['name'],
                        'hasPermission' => true
                    ];
                } else {
                    $permissions[] = [
                        'id' => $permission['id'],
                        'name' => $permission['name'],
                        'hasPermission' => false
                    ];
                }
            }
            $response = $role;
            $response['permissionStatus'] = $permissions;
            return response()->json($response);
        }
        $role->permissions;
        return response()->json($role);
    }

    public function update(Role $role, UpdateUserRoleRequest $request)
    {

        if($request->hasPermission && !$role->hasPermissionTo($request->name)) {
            $role->givePermissionTo($request->name);
        }
        if (!$request->hasPermission && $role->hasPermissionTo($request->name)) {
            $role->revokePermissionTo($request->name);
        }
        return [
            'saved' => true,
            'message' => 'Successfully saved Permission'
        ];
    }

}
