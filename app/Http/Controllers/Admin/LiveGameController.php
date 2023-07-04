<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\League;
use App\Models\Season;
use App\Models\Team;
use App\Models\Goals;
use App\Models\YellowCard;
use Illuminate\Support\Facades\Session;
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

        // Dodaj dane goli do $gameData
        $gameData['goals'] = $goals;
        $gameData['yellowCards'] = $yellowCards;

        return response()->json($gameData);
    }

    public function getPlayersByTeamAndSeason($teamId, $seasonId)
    {
        $players = Player::whereHas('playerSeasons', function ($query) use ($teamId, $seasonId) {
            $query->where('team_id', $teamId)->where('season_id', $seasonId);
        })->get(['name', 'surname', 'id']);

        return response()->json(['players' => $players]);
    }



    public function setActiveGame(Request $request, $gameId)
    {
        // Pobierz przesłane ID gry
        $activeGameId = $request->input('game_id');

        // Wykonaj odpowiednie operacje na podstawie przesłanego ID gry
        // np. zapisz ID gry do sesji lub zmiennej globalnej

        // Zwróć odpowiedź z sukcesem (status 200)
        return response()->json(['message' => 'ID gry zostało ustawione: ' . $gameId], 200);
    }

    public function storeGoal(Request $request)
    {

        $option = $request->input('options');

        switch ($option) {
            case 2:
                $this->storeGoalOption2($request);
                break;

            case 3:
                $this->storeGoalOption3($request);
                break;

            case 'option4':
                // Zapisz dane dla opcji "2x Żółta kartka"
                // ...
                break;

            // Dodaj obsługę pozostałych opcji

            default:
                // Obsługa dla przypadku, gdy żadna z opcji nie pasuje
                break;
        }

        // Zwróć odpowiedź AJAX
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        } else {
            return redirect()->intended('livegame')->with('success', 'Dane zostały zapisane.');
        }
    }

    public function deleteGoal($goalId)
    {
        // Sprawdź, czy bramka istnieje
        $goal = Goals::find($goalId);
        $isGoal = true;

        // Jeśli bramka nie istnieje, sprawdź, czy to żółta kartka
        if (!$goal) {
            $goal = YellowCard::find($goalId);
            $isGoal = false;
        }

        if ($goal) {
            // Znajdź mecz powiązany z wydarzeniem
            $game = Game::findOrFail($goal->game_id);

            // Usuń bramkę lub żółtą kartkę
            $goal->delete();

            // Zaktualizuj wynik w tabeli games
            $goals = Goals::where('game_id', $game->id)->get();
            $yellowCards = YellowCard::where('game_id', $game->id)->get();

            $result1 = $goals->where('team_id', $game->team1->id)->count();
            $result2 = $goals->where('team_id', $game->team2->id)->count();

            $game->result1 = $result1;
            $game->result2 = $result2;
            $game->save();

            // Zwróć odpowiedź w formacie JSON
            $message = $isGoal ? 'Goal deleted successfully' : 'Yellow card deleted successfully';
            return response()->json(['message' => $message]);
        } else {
            // Bramka lub żółta kartka o podanym ID nie została znaleziona
            return response()->json(['error' => 'Goal or yellow card not found'], 404);
        }
    }

    private function storeGoalOption2(Request $request)
    {
        // Zapisz dane dla opcji "Bramka"
        $goal = new Goals();
        $goal->game_id = $request->input('game_id');
        $goal->player_id = $request->input('player_id');
        $goal->minute = $request->input('minute');
        $goal->team_id = $request->input('team');
        $goal->league_id = $request->input('league_id');
        $goal->season_id = $request->input('season_id');
        $goal->save();

        // Zlicz bramki dla team_id i game_id
        $team1GoalsCount = Goals::where('team_id', $request->input('team'))
            ->where('game_id', $request->input('game_id'))
            ->count();

        $team2GoalsCount = Goals::where('team_id', '!=', $request->input('team'))
            ->where('game_id', $request->input('game_id'))
            ->count();

        // Sprawdź, czy drużyna posiada już jakiekolwiek gole w danej grze
        if ($team1GoalsCount === 0 && $team2GoalsCount === 0) {
            // Ustaw wartość 0 dla obu drużyn
            $team1GoalsCount = 0;
            $team2GoalsCount = 0;
        } else {
            // Drużyna ma już gole, nie zmieniaj wartości
        }

        // Aktualizuj rekord w tabeli "games" na podstawie wyników
        $game = Game::find($request->input('game_id'));
        if ($game) {
            if ($request->input('team') == $game->team1_id) {
                $game->result1 = $team1GoalsCount;
                $game->result2 = $team2GoalsCount;
            } else {
                $game->result1 = $team2GoalsCount;
                $game->result2 = $team1GoalsCount;
            }
            $game->save();
        }
    }

    private function storeGoalOption3(Request $request)
    {
        $yellowcard = new YellowCard();
        $yellowcard->game_id = $request->input('game_id');
        $yellowcard->player_id = $request->input('player_id');
        $yellowcard->minute = $request->input('minute');
        $yellowcard->team_id = $request->input('team');
        $yellowcard->save();
    }
}
