<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\PlayerController;
use App\Http\Controllers\Admin\TeamController;
use Illuminate\Support\Facades\Route;

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

Route::get('test', [\App\Http\Controllers\GameController::class, 'index'])->name('test');
Route::get('test2', [\App\Http\Controllers\StandingController::class, 'index'])->name('test2');
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post');
Route::get('registration', [AuthController::class, 'registration'])->name('register');
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post');

Route::group(['middleware' => ['auth']], function () {

Route::get('dashboard', [AuthController::class, 'dashboard']);

Route::get('players', [PlayerController::class, 'index']);
Route::get('teams', [TeamController::class, 'index']);
Route::get('games', [GameController::class, 'index']);
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/players',[PlayerController::class, 'store'])->name('player.store');
Route::get('deleted',[PlayerController::class, 'destroy'])->name('player.delete');

Route::get('/update', [PlayerController::class, 'edit'])->name('player.update');
    Route::post('/update', [PlayerController::class, 'update'])->name('update');

    Route::post('/games',[GameController::class, 'store'])->name('games.store');
    Route::get('gameDeleted',[GameController::class, 'destroy'])->name('game.delete');

    Route::resource('games', \App\Http\Controllers\Admin\GameController::class);



});

