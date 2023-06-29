<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\League;
use App\Models\Season;
use App\Models\Team;
use App\Models\Player;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LiveGameController extends Controller
{
    public function index(): View
    {
        $leagues = League::pluck('name', 'id');
        $teams = Team::pluck('name', 'id');
        $seasons = Season::all();

        $selectedGames = [];

        // Sprawdź, czy istnieją zapisane gry w pamięci podręcznej
        if (isset($_COOKIE['selectedGames'])) {
            $selectedGames = json_decode($_COOKIE['selectedGames'], true);
        }

        // Pobierz tylko gry, których ID znajduje się w pamięci podręcznej
        $games = Game::whereIn('id', $selectedGames)->get();

        return view('admin.livegame', compact('games', 'teams', 'leagues', 'seasons', 'selectedGames'));
    }

    public function updateGameData(Request $request, $gameId)
    {
        // Pobierz dane meczu z bazy danych na podstawie $gameId
        $game = Game::findOrFail($gameId);

        // Przygotuj dane meczu do przesłania do widoku
        $gameData = [
            'team1Name' => $game->team1->name,
            'team1Id' => $game->team1->id,
            'result1' => $game->result1,
            'result2' => $game->result2,
            'team2Name' => $game->team2->name,
            'team2Id' => $game->team2->id,
            'round' => $game->round,
            'league' => $game->league->name,
            'seasonID' => $game->season->id,
            'season' => $game->season->name,
        ];

        $players = Player::where('team_id', $game->team1->id)->orWhere('team_id', $game->team2->id)->get(['name', 'id']);
        $gameData['players'] = $players;

        return response()->json($gameData);
    }

    public function getPlayersByTeamAndSeason($teamId, $seasonId)
    {
        $players = Player::whereHas('playerSeasons', function ($query) use ($teamId, $seasonId) {
            $query->where('team_id', $teamId)->where('season_id', $seasonId);
        })->get(['name', 'surname', 'id']);

        return response()->json(['players' => $players]);
    }
}
