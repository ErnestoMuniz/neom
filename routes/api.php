<?php

use App\Http\Controllers\ActionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\OltController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('recover-password', [UserController::class, 'sendPasswordRecoverMail']);
Route::post('change-password', [UserController::class, 'resetPassword']);
Route::middleware('CheckToken')->group(function () {
  Route::apiResource('users', UserController::class)->middleware('can:edit_users');
  Route::apiResource('roles', RoleController::class)->middleware('can:edit_roles');
  Route::get('getOlts', [OltController::class, 'publicIndex'])->middleware('can:view_onus');
  Route::get('getOlts/{olt}', [OltController::class, 'publicShow'])->middleware('can:view_onus');
  Route::apiResource('permissions', PermissionController::class)->middleware('can:edit_permissions');
  Route::apiResource('olts', OltController::class)->middleware('can:edit_olts');
  Route::get('toggleOlt/{id}', [OltController::class, 'toggleOlt'])->middleware('can:edit_olts');
  Route::post('exec/{olt}/{cmd}', [ActionController::class, 'router'])->middleware('log');

  //Log Routes
  Route::prefix('log')->group(function () {
    Route::get('count', [LogController::class, 'actionCount']);
    Route::get('count/user', [LogController::class, 'actionUserCount']);
  });

  // Statistics Routes
  Route::get('stats/olts', [StatsController::class, 'olts']);
  Route::get('stats/users', [StatsController::class, 'users']);
  Route::get('stats/roles', [StatsController::class, 'roles']);
  Route::get('stats/actions', [StatsController::class, 'actions']);
});
Route::any('nothing', function () {
  response()->noContent();
});