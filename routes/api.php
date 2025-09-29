<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SchoolController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\WpController;
use Spatie\Permission\Middlewares\PermissionMiddleware;

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
    $user = $request->user();

    return response()->json([
        'user' => $user,
        'roles' => $user->getRoleNames(),
        'permissions' => $user->getAllPermissions()->pluck('name'),
    ]);
});


// Authentication, Registration
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


// Schools
// Public: view schools
Route::get('schools', [SchoolController::class, 'index']);
Route::get('schools/{id}', [SchoolController::class, 'show']);
//Route::apiResource('schools', SchoolController::class);

Route::get('countries', [LocationController::class, 'getAllCountries']);
Route::get('countries/{id}/provinces', [LocationController::class, 'getCountryProvinces']);
Route::get('provinces/{id}/cities', [LocationController::class, 'getProvinceCities']);


// Authenticated routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('schools', [SchoolController::class, 'store'])->middleware('permission:manage schools');
    Route::put('schools/{school}', [SchoolController::class, 'update'])->middleware('permission:manage schools');
    Route::delete('schools/{school}', [SchoolController::class, 'destroy'])->middleware('permission:manage schools');
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('users', UserController::class);

    Route::get('/wp-login', [WpController::class, 'goToWordPress']);

});
