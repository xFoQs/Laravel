<?php

namespace App\Http\Controllers\Admin;

use App\Models\Game;
use App\Models\Team;
use App\Models\League;
use App\Models\Season;
use App\Models\User;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class GameController extends Controller
{

    public function index(): View
    {
        $games = Game::all();
        $users = User::all();
        $leagues = League::pluck('name', 'id');
        $teams = Team::pluck('name', 'id');
        $seasons = Season::all();

        $selectedGames = [];

        // Sprawdź, czy istnieją zapisane gry w pamięci podręcznej
        if (isset($_COOKIE['selectedGames'])) {
            $selectedGames = json_decode($_COOKIE['selectedGames'], true);
        }

        return view('admin.games', compact('games', 'teams', 'leagues', 'seasons', 'selectedGames','users'));
    }

    public function store(Request $request)
    {
        $games = new Game();
        $games->team1_id = $request->input('team1_id');
        $games->team2_id = $request->input('team2_id');
        $games->league_id = $request->input('league_id');
        $games->start_time = $request->input('start_time');
        $games->round = $request->input('round');
        $games->result1 = $request->input('result1');
        $games->result2 = $request->input('result2');
        $games->season_id = $request->input('season_id');
        $games->save();

        return redirect()->intended('games')->withSuccess('Rekord został dodany pomyślnie');
    }

    public function show(Game $game): View
    {
        return view('admin.games.show', compact('game'));
    }

    public function edit(Game $game): View
    {
        $teams = Team::all()->pluck('name', 'id');

        return view('admin.games', compact('game', 'teams'));
    }

    public function update(Request $request)
    {

        $game = Game::findOrFail($request->id);

        $game->team1_id = $request->input('team1_id_edit');
        $game->team2_id = $request->input('team2_id_edit');
        $game->league_id = $request->input('league_id_edit');
        $game->start_time = $request->input('start_time');
        $game->round = $request->input('round_edit');
        $game->result1 = $request->input('result1');
        $game->result2 = $request->input('result2');
        $game->season_id = $request->input('season_id');

        $game->save();

        return redirect()->intended('games')->withUpdate('Rekord został zaktualizowany pomyślnie');

    }

    public function destroy(Request $id)
    {
        $games = Game::find($id->event_id);
        $games->delete();

        return $games;
    }

    public function massDestroy()
    {
        Game::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }


    public function getTeamsByLeague(Request $request)
    {
        $leagueId = $request->input('league_id');
        $teamsInLeague = Team::where('league_id', $leagueId)->get();

        return response()->json($teamsInLeague);
    }

    public function getTeamsByLeagueEdit(Request $request)
    {
        $leagueId = $request->input('league_id_edit');
        $teamsInLeague = Team::where('league_id', $leagueId)->get();

        return response()->json($teamsInLeague);
    }

    public function getGameTeams(Request $request)
    {
        $gameId = $request->input('game_id');
        $game = Game::find($gameId, ['team1_id', 'team2_id', 'round']);

        return response()->json($game);
    }

    public function showSelectedGames()
    {
        $storedGames = json_decode(request()->session()->get('selectedGames'), true);

        // Pobierz wszystkie dane dla tabeli games dla przechowywanych identyfikatorów
        $games = Game::whereIn('id', $storedGames)->get();

        // Przekazanie danych do widoku
        return view('admin.livegame', ['games' => $games]);
    }



}
