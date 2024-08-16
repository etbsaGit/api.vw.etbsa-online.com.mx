<?php

use App\Http\Controllers\PermissionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
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

Route::middleware(['auth:sanctum', 'cors'])->group(function () {
    //--------------------User--------------------
    Route::get('user/options', [UserController::class, 'getOptions']);
    Route::post('auth/logout', [UserController::class, 'logout']);
    Route::post('auth/change', [UserController::class, 'changePassword']);
    Route::apiResource('user', UserController::class);

    //--------------------Role--------------------
    Route::apiResource('role', RoleController::class);
    Route::apiResource('permission', PermissionController::class);
});

Route::post('auth/user', [UserController::class, 'store']);
Route::post('auth/login', [UserController::class, 'login']);
