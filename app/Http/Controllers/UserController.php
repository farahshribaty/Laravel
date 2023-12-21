<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CustomerRequest;
use App\Http\Requests\CustomerLoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\AssignPermissionRequest;
use App\Http\Requests\AssignRoleRequest;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function register(CustomerRequest $request) {

        $validatedData = $request->validated();
        $user = User::create($validatedData);

        if($request->hasFile('image')) {
            $user->image = $this->addImage($request->file('image'));
        }
        $token = $user->createToken('apiToken')->plainTextToken;
        
        return response()->json([
            'success'=>true,
            'data'=>$user,
            'token'=> $token
        ]);
    }

    public function login(CustomerLoginRequest $request) {
        $data = $request->validated();
        $user = User::where('username', $data['username'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response([
                'msg' => 'incorrect username or password'
            ], 401);
        }
        $token = $user->createToken('apiToken')->plainTextToken;
        return response()->json([
        'success'=>true,
        'data'=>$user,
        'token'=> $token
        ]);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'user logged out'
        ];
    }

    public function addImage($image) {
        $name = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = "/home/mhdaklor/discount.mhdanas.com/public/Images/customer";
        $image->move($destinationPath, $name);
        return  '/Images/customer/'.$name;
    }

    public function getAllCategoriesWithSearch(Request $request) {
        $searchTerm = $request->input('search');

        $categories = Category::when($searchTerm, function ($query) use ($searchTerm) {
            return $query->where('name', 'like', "%$searchTerm%")
                         ->orWhere('id', $searchTerm);
        })->get();

        return response()->json($categories, 200);
    }

    public function getProductsByCategory(Request $request, $categoryId) {
        $products = Product::where('category_id', $categoryId)->get();

        return response()->json($products, 200);
    }

    public function getFiveCheapestProducts() {
        $cheapestProducts = Product::orderBy('price','ASC')->limit(5)->get();

        return response()->json($cheapestProducts, 200);
    }

    public function assignRole(AssignRoleRequest $request, $userId) {
        $user = User::findOrFail($userId);
        $role = Role::where('name', $request->input('role'))->firstOrFail();

        $user->assignRole($role);

        return response()->json(['message' => 'Role assigned successfully']);
    }

    // public function assignPermission(AssignPermissionRequest $request, $userId) {
    //     $user = User::findOrFail($userId);
    //     $permission = Permission::where('name', $request->input('permission'))->firstOrFail();

    //     $user->givePermissionTo($permission);

    //     return response()->json(['message' => 'Permission assigned successfully']);
    // }
    public function assignPermission(AssignPermissionRequest $request, $userId) {
    
        $role = DB::table('model_has_roles')
        ->where('model_id', $userId)
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->select('roles.name')
        ->get();
        // return $role;
        $permission = Permission::where('name', $request->input('permission'))->firstOrFail();
        $role->givePermissionTo($permission);

        return response()->json(['message' => 'Permission assigned successfully']);
        
    }

}
