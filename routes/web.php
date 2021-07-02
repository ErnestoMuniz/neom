<?php

use App\Http\Controllers\Controller;
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
    return redirect('login');
});

Route::get('/home', function () {
    return redirect('dashboard');
});
Route::get('/dashboard', [Controller::class, 'initial'])->middleware(['auth'])->name('dashboard');
Route::get('/navigate', [Controller::class, 'navigate'])->middleware(['auth'])->name('navigate');

Route::get('/get/{vendor}/{function}', array('uses' => 'App\Http\Controllers\Controller@teste'));
