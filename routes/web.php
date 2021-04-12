<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\OltController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [Controller::class, 'initial'])->middleware(['auth'])->name('dashboard');
Route::get('/navigate', [Controller::class, 'navigate'])->middleware(['auth'])->name('navigate');

// Rotas de OLTs
Route::get('/olts', [Controller::class, 'olts'])->middleware(['auth'])->name('olts');
Route::post('/newOlt', [OltController::class, 'newOlt'])->middleware(['auth'])->name('newOlt');
Route::post('/editOlt', [OltController::class, 'editOlt'])->middleware(['auth'])->name('editOlt');
Route::get('/removeOlt', [OltController::class, 'removeOlt'])->middleware(['auth'])->name('removeOlt');

// Rotas de Usuários
Route::get('/users', [UserController::class, 'users'])->middleware(['auth'])->name('users');
Route::post('/newUser', [UserController::class, 'newUser'])->middleware(['auth'])->name('newUser');
Route::post('/editUser', [UserController::class, 'editUser'])->middleware(['auth'])->name('editUser');
Route::get('/removeUser', [UserController::class, 'removeUser'])->middleware(['auth'])->name('removeUser');

// Utilitários
Route::get('/pon', [Controller::class, 'pon'])->name('pon');
Route::get('/onu', [Controller::class, 'onu'])->name('onu');
Route::get('/mem', [Controller::class, 'mem'])->name('mem');
Route::get('/cpu', [Controller::class, 'cpu'])->name('cpu');
Route::get('/firmware', [Controller::class, 'firmware'])->name('firmware');

require __DIR__.'/auth.php';
