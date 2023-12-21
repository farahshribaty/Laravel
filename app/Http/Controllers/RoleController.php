<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return response()->json($roles, 200);
    }

    public function store(Request $request)
    {
        $role = Role::create(['name' => $request->name]);
        return response()->json($role, 201);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findById($id);
        $role->name = $request->name;
        $role->save();
        return response()->json($role, 200);
    }

    public function destroy($id)
    {
        $role = Role::findById($id);
        $role->delete();
        return response()->json(null, 204);
    }
}
