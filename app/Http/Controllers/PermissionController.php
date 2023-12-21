<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return response()->json($permissions, 200);
    }

    public function store(Request $request)
    {
        $permission = Permission::create(['name' => $request->name]);
        return response()->json($permission, 201);
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::findById($id);
        $permission->name = $request->name;
        $permission->save();
        return response()->json($permission, 200);
    }

    public function destroy($id)
    {
        $permission = Permission::findById($id);
        $permission->delete();
        return response()->json(null, 204);
    }
}
