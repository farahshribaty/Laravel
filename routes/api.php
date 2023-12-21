<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::prefix('customer')->group(function () {
        Route::post('login', [UserController::class, 'login']);
        Route::get('logout', [UserController::class, 'logout']);
        Route::get('categories', [UserController::class, 'getAllCategoriesWithSearch']);
        Route::get('category/{categoryId}', [UserController::class, 'getProductsByCategory']);
        Route::get('cheapest', [UserController::class, 'getFiveCheapestProducts']);
    });
});

Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);
Route::get('getByCategoryId/{categoryId}', [ProductController::class,'getByCategory']);

Route::apiResource('roles', RoleController::class);
Route::apiResource('permissions', PermissionController::class);
Route::post('roles/{roleId}/permissions', [RolePermissionController::class,'attach']);
Route::delete('roles/{roleId}/permissions', [RolePermissionController::class,'detach']);

Route::post('assignRole/{userId}', [UserController::class,'assignRole']);
Route::post('assignPermission/{userId}', [UserController::class,'assignPermission']);
