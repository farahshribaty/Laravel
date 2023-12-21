<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    public function attach(Request $request, $roleId)
    {
        $role = Role::findById($roleId);
        $permission = Permission::findById($request->permissionId);
        $role->givePermissionTo($permission);
        return response()->json($role->permissions, 200);
    }

    public function detach(Request $request, $roleId)
    {
        $role = Role::findById($roleId);
        $permission = Permission::findById($request->permissionId);
        $role->revokePermissionTo($permission);
        return response()->json($role->permissions, 200);
    }

    
}
