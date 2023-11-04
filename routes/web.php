<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\PlayerController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\Admin\UserController;
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

Route::get('/page', function () {
    return view('page');
});

Route::get('/', [\App\Http\Controllers\IndexController::class, 'index'])->name('page');
Route::get('tournament', [\App\Http\Controllers\TournamentController::class, 'index'])->name('tournament');
Route::get('schedule', [\App\Http\Controllers\GameController::class, 'index'])->name('schedule');
Route::get('test', [\App\Http\Controllers\GameController::class, 'index'])->name('test');
Route::get('test2', [\App\Http\Controllers\StandingController::class, 'index'])->name('test2');
Route::get('/standings', [\App\Http\Controllers\StandingController::class, 'index'])->name('standings');
Route::get('score', [\App\Http\Controllers\StandingController::class, 'getBalance'])->name('score');
Route::get('/player', [\App\Http\Controllers\PlayerController::class, 'index'])->name('player.filter');
Route::get('/player/count-goals-in-game/{player}/{gameId}', [\App\Http\Controllers\PlayerController::class,'countGoalsInGame'])->name('player.count_goals_in_game');
Route::get('test3', [\App\Http\Controllers\PlayerController::class, 'index'])->name('test3');
Route::get('/chart', [\App\Http\Controllers\StatisticsController::class, 'chart'])->name('chart');
Route::get('/statistics', [\App\Http\Controllers\StatisticsController::class, 'index'])->name('statistics');
Route::get('test4', [\App\Http\Controllers\StatisticsController::class, 'index'])->name('test4');
Route::get('/game_data', [\App\Http\Controllers\GameData::class, 'index'])->name('gameData.index');
Route::get('/gamedata/{gameId}', [\App\Http\Controllers\GameData::class, 'updateGameData']);




Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post');

Route::group(['middleware' => ['auth']], function () {


    Route::middleware(['auth', 'checkUserRole'])->group(function () {
        Route::get('/users',  [UserController::class, 'index'])->name('users');
        // Dodaj inne trasy wymagające roli 1 tutaj
    });

Route::get('registration', [AuthController::class, 'registration'])->name('register');
Route::get('dashboard', [AuthController::class, 'dashboard']);
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post');

Route::get('players', [PlayerController::class, 'index']);
Route::get('teams', [TeamController::class, 'index']);
Route::get('games', [GameController::class, 'index']);

Route::get('seasons', [\App\Http\Controllers\Admin\SeasonController::class, 'index']);

    Route::get('leagues', [\App\Http\Controllers\Admin\LeagueController::class, 'index']);

    Route::post('/leagues',[\App\Http\Controllers\Admin\LeagueController::class, 'store'])->name('league.store');

    Route::get('/leaguesdeleted', [\App\Http\Controllers\Admin\LeagueController::class, 'destroy'])->name('leagues.delete');

    Route::post('/leaguesupdated', [\App\Http\Controllers\Admin\LeagueController::class, 'update'])->name('league.update');


Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/players',[PlayerController::class, 'store'])->name('player.store');
Route::post('/seasons',[\App\Http\Controllers\Admin\SeasonController::class, 'store'])->name('season.store');
Route::get('/seasonsdeleted', [\App\Http\Controllers\Admin\SeasonController::class, 'destroy'])->name('seasons.delete');
Route::post('/teams',[TeamController::class, 'store'])->name('team.store');
Route::get('deleted',[PlayerController::class, 'destroy'])->name('player.delete');
Route::get('teamsdeleted',[TeamController::class, 'destroy'])->name('team.delete');
Route::post('/teamsupdated', [TeamController::class, 'update'])->name('team.update');
    Route::post('/seasonsupdated', [\App\Http\Controllers\Admin\SeasonController::class, 'update'])->name('season.update');

Route::get('/update', [PlayerController::class, 'edit'])->name('player.update');
    Route::post('/update', [PlayerController::class, 'update'])->name('update');

    Route::post('/games',[GameController::class, 'store'])->name('games.store');
    Route::get('gameDeleted',[GameController::class, 'destroy'])->name('game.delete');

    Route::resource('games', \App\Http\Controllers\Admin\GameController::class);

    Route::get('/get-teams-by-league', [GameController::class, 'getTeamsByLeague'])->name('getTeamsByLeague');
    Route::get('/get-teams-by-league-edit', [GameController::class, 'getTeamsByLeagueEdit'])->name('get.teams.by.league.edit');
    Route::get('/get-game-teams', [GameController::class, 'getGameTeams']);
    Route::get('/get-game-live', [GameController::class, 'getGameLive']);

    Route::get('/livegame',[\App\Http\Controllers\Admin\LiveGameController::class, 'index']);
    Route::post('/select-game', [LiveGameController::class, 'selectGame'])->name('select.game');
    Route::post('/admin/games/{gameId}/status', [\App\Http\Controllers\Admin\LiveGameController::class, 'updateGameStatus']);
    Route::get('/admin/livegame/{gameId}', [App\Http\Controllers\Admin\LiveGameController::class, 'updateGameData'])->name('admin.livegame.updateGameData');

    Route::get('/players/{teamId}/{seasonId}', [App\Http\Controllers\Admin\LiveGameController::class, 'getPlayersByTeamAndSeason']);
    Route::get('/goals', [App\Http\Controllers\Admin\LiveGameController::class, 'storeGoal'])->name('goals.store');

    Route::get('/delete-goal/{goalId}', [App\Http\Controllers\Admin\LiveGameController::class,'deleteGoal']);





});

