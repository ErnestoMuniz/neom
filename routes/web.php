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
    return redirect()->route('login');
});

Route::get('/dashboard', [Controller::class, 'initial'])->middleware(['auth'])->name('dashboard');
Route::get('/navigate', [Controller::class, 'navigate'])->middleware(['auth'])->name('navigate');

// Função para retornar informações das OLTs
Route::get('/get/{vendor}/{function}', array('uses' => 'App\Http\Controllers\Controller@teste'));

require __DIR__.'/auth.php';


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
