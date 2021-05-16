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

// Utilitários
Route::get('/pon', [Controller::class, 'pon'])->name('pon');
Route::get('/onu', [Controller::class, 'onu'])->name('onu');
Route::get('/mem', [Controller::class, 'mem'])->name('mem');
Route::get('/cpu', [Controller::class, 'cpu'])->name('cpu');
Route::get('/firmware', [Controller::class, 'firmware'])->name('firmware');

require __DIR__.'/auth.php';


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
