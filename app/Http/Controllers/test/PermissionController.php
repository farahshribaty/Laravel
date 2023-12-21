<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function setupRolesAndPermissions()
    {
        // Create roles
        $superAdminRole = Role::create(['name' => 'super admin']);
        $adminRole = Role::create(['name' => 'admin']);

        // Create permissions
        $createOrderPermission = Permission::create(['name' => 'create order']);
        $getOrderPermission = Permission::create(['name' => 'get order']);

        // Assign permissions to roles
        $superAdminRole->givePermissionTo($createOrderPermission, $getOrderPermission);
        $adminRole->givePermissionTo($getOrderPermission);

        return response()->json(['message' => 'Roles and permissions set up successfully']);
    }
}
