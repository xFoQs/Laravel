<?php


namespace App\Http\Controllers;

use App\Models\Change;
use App\Models\Game;
use App\Models\Information;
use App\Models\League;
use App\Models\MissedPenalty;
use App\Models\Player;
use App\Models\Season;
use App\Models\Goals;
use App\Models\SuicideGoal;
use App\Models\YellowCard;
use App\Models\YellowCard2;
use Illuminate\Http\Request;

class GameData extends Controller
{
    public function index(Request $request)
    {
        // Pobierz parametr "game_id" z adresu URL
        $gameId = $request->query('game_id');

        // Tutaj możesz dodać logikę do pobrania danych meczu na podstawie przekazanego identyfikatora $gameId
        // Możemy użyć modelu "Game" do pobrania danych na podstawie "game_id"
        $game = Game::find($gameId);

        // Sprawdzamy, czy istnieje mecz o podanym "game_id"
        if ($game) {
            // Jeśli mecz został znaleziony, możemy przekazać go do widoku "game_data"
            return view('game_data', ['game' => $game]);
        } else {
            // Jeśli mecz o podanym "game_id" nie został znaleziony, możemy obsłużyć ten przypadek
            // na przykład przekierować użytkownika lub wyświetlić odpowiedni komunikat
            return redirect()->back()->with('error', 'Mecz o podanym ID nie został znaleziony.');
        }
    }

    public function updateGameData(Request $request, $gameId)
    {
        // Pobierz dane meczu z bazy danych na podstawie $gameId
        $game = Game::findOrFail($gameId);

        // Pobierz zdjęcia drużyn
        $team1Logo = $game->team1->photo;
        $team2Logo = $game->team2->photo;

        // Przygotuj dane meczu do przesłania do widoku
        $gameData = [
            'team1Name' => $game->team1->name,
            'team1Id' => $game->team1->id,
            'result1' => $game->result1,
            'result2' => $game->result2,
            'team2Name' => $game->team2->name,
            'team1Logo' => $team1Logo, // Dodaj logo drużyny 1
            'team2Logo' => $team2Logo, // Dodaj logo drużyny 2
            'team2Id' => $game->team2->id,
            'round' => $game->round,
            'status' => $game->status,
            'league' => $game->league->name,
            'leagueID' => $game->league->id,
            'seasonID' => $game->season->id,
            'season' => $game->season->name,
        ];

        $players = Player::where('team_id', $game->team1->id)->orWhere('team_id', $game->team2->id)->get(['name', 'id']);
        $gameData['players'] = $players;

        // Pobierz dane z tabeli "goals" na podstawie game_id
        $goals = Goals::where('game_id', $gameId)->with('player')->get();
        // Pobierz dane z tabeli "yellowcard" na podstawie game_id
        $yellowCards = YellowCard::where('game_id', $gameId)->with('player')->get();
        $yellowcard2 = YellowCard2::where('game_id', $gameId)->with('player')->get();
        $missedpenalty = MissedPenalty::where('game_id', $gameId)->with('player')->get();
        $suicidegoals = SuicideGoal::where('game_id', $gameId)->with('player')->get();
        $message = Information::where('game_id', $gameId)->get();
        $change = Change::where('game_id', $gameId)->with('player')->get();


        // Dodaj dane goli do $gameData
        $gameData['goals'] = $goals;
        $gameData['yellowCards'] = $yellowCards;
        $gameData['yellowCards2'] = $yellowcard2;
        $gameData['missedpenalty'] = $missedpenalty;
        $gameData['suicidegoals'] = $suicidegoals;
        $gameData['message'] = $message;
        $gameData['change'] = $change;

        return response()->json($gameData);
    }


}
