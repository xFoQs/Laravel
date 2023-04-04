<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PlayersController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post'); 
Route::get('registration', [AuthController::class, 'registration'])->name('register');
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post'); 

Route::group(['middleware' => ['auth']], function () {

Route::get('dashboard', [AuthController::class, 'dashboard']);
Route::get('players', [PlayersController::class, 'index']);
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::post('players',[PlayersController::class, 'store']);
Route::get('deleted',[PlayersController::class, 'destroy'])->name('player.delete');

Route::get('update',[PlayersController::class, 'update'])->name('player.update');

});

