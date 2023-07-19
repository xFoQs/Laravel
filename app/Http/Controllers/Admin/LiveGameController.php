<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Change;
use App\Models\Game;
use App\Models\Information;
use App\Models\League;
use App\Models\MissedPenalty;
use App\Models\Season;
use App\Models\SuicideGoal;
use App\Models\Team;
use App\Models\Goals;
use App\Models\YellowCard;
use App\Models\YellowCard2;
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

            case 4:
                // Zapisz dane dla opcji "2x Żółta kartka"
                // ...
                $this->storeYellowcard($request);
                break;

            // Dodaj obsługę pozostałych opcji

            case 5:
                // Zapisz dane dla opcji "Niewykorzysany rzut karny"
                // ...
                $this->storeMissedPenalty($request);
                break;

            case 6:
                // Zapisz dane dla opcji "Niewykorzysany rzut karny"
                // ...
                $this->storeSuicideGoals($request);
                break;

            // Dodaj obsługę pozostałych opcji

            case 1:
                $this->storeInformation($request);
                break;

            case 7:
                $this->storeChange($request);
                break;

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
        $goal = Goals::find($goalId);

        if ($goal) {
            // Usuń bramkę
            $this->deleteRegularGoal($goal);
        } else {
            $suicideGoal = SuicideGoal::find($goalId);

            if ($suicideGoal) {
                // Usuń bramkę samobójczą
                $this->deleteSuicideGoal($suicideGoal);
            } else {
                $yellowCard = YellowCard::find($goalId);

                if ($yellowCard) {
                    // Usuń żółtą kartkę
                    $this->deleteYellowCard($yellowCard);
                } else {
                    $yellowCard2 = YellowCard2::find($goalId);

                    if ($yellowCard2) {
                        // Usuń drugą żółtą kartkę
                        $this->deleteYellowCard2($yellowCard2);
                    } else {
                        $missedPenalty = MissedPenalty::find($goalId);

                        if ($missedPenalty) {
                            // Usuń niewykorzystany karny
                            $this->deleteMissedPenalty($missedPenalty);
                        } else {
                            $information = Information::find($goalId);

                            if ($information) {
                                // Usuń informację
                                $this->deleteInformation($information);
                            } else {
                                $change = Change::find($goalId);

                                if ($change) {
                                    // Usuń zmianę
                                    $this->deleteChange($change);
                                } else {
                                    // Zdarzenie o podanym ID nie zostało znalezione
                                    return response()->json(['error' => 'Event not found'], 404);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private function deleteRegularGoal($goal)
    {
        $game = Game::findOrFail($goal->game_id);
        $team1Id = $game->team1_id;
        $team2Id = $game->team2_id;

        // Usuń bramkę
        $goal->delete();

        // Zaktualizuj wynik dla zwykłych bramek
        $team1GoalsCount = Goals::where('team_id', $team1Id)
            ->where('game_id', $game->id)
            ->count();

        $team2GoalsCount = Goals::where('team_id', $team2Id)
            ->where('game_id', $game->id)
            ->count();

        // Zlicz bramki samobójcze dla obu drużyn
        $team1SuicideGoalsCount = SuicideGoal::where('team_id', $team2Id)
            ->where('game_id', $game->id)
            ->count();

        $team2SuicideGoalsCount = SuicideGoal::where('team_id', $team1Id)
            ->where('game_id', $game->id)
            ->count();

        // Zaktualizuj wynik na podstawie liczby bramek zwykłych i samobójczych
        $result1 = $team1GoalsCount + $team1SuicideGoalsCount;
        $result2 = $team2GoalsCount + $team2SuicideGoalsCount;

        $game->result1 = $result1;
        $game->result2 = $result2;
        $game->save();

        // Zwróć odpowiedź w formacie JSON
        return response()->json(['message' => 'Goal deleted successfully']);
    }

    private function deleteInformation($information)
    {
        // Usuń informację
        $information->delete();

        // Zwróć odpowiedź w formacie JSON
        return response()->json(['message' => 'Information deleted successfully']);
    }

    private function deleteYellowCard2($yellowCard2)
    {
        // Usuń drugą żółtą kartkę
        $yellowCard2->delete();
    }

    private function deleteChange($change)
    {
        // Usuń drugą żółtą kartkę
        $change->delete();
    }

    private function deleteYellowCard($yellowCard)
    {
        // Usuń żółtą kartkę
        $yellowCard->delete();
    }


    private function deleteMissedPenalty($missedpenalty)
    {
        // Usuń niewykorzystany karny
        $missedpenalty->delete();
    }

    private function deleteSuicideGoal($goal)
    {
        $game = Game::findOrFail($goal->game_id);

        // Usuń bramkę samobójczą
        $goal->delete();

        // Zaktualizuj wynik dla bramek samobójczych
        $team1SuicideGoalsCount = SuicideGoal::where('team_id', $game->team1_id)
            ->where('game_id', $game->id)
            ->count();

        $team2SuicideGoalsCount = SuicideGoal::where('team_id', $game->team2_id)
            ->where('game_id', $game->id)
            ->count();

        $suicideGoalsCount = $team1SuicideGoalsCount + $team2SuicideGoalsCount;

        // Zaktualizuj wynik dla zwykłych bramek
        $team1GoalsCount = Goals::where('team_id', $game->team1_id)
            ->where('game_id', $game->id)
            ->count();

        $team2GoalsCount = Goals::where('team_id', $game->team2_id)
            ->where('game_id', $game->id)
            ->count();

        $game->result1 = $team1GoalsCount + $suicideGoalsCount;
        $game->result2 = $team2GoalsCount + $suicideGoalsCount;
        $game->save();

        // Zwróć odpowiedź w formacie JSON
        return response()->json(['message' => 'Suicide goal deleted successfully']);
    }


    private function storeGoalOption3(Request $request)
    {
        $yellowcard = new YellowCard();
        $yellowcard->game_id = $request->input('game_id');
        $yellowcard->player_id = $request->input('player_id');
        $yellowcard->minute = $request->input('minute');
        $yellowcard->team_id = $request->input('team');
        $yellowcard->league_id = $request->input('league_id');
        $yellowcard->season_id = $request->input('season_id');
        $yellowcard->message = $request->input('message'); // Odczytaj wartość pola 'message'
        $yellowcard->save();
    }

    private function storeYellowcard(Request $request)
    {
        $yellowcard2 = new YellowCard2();
        $yellowcard2->game_id = $request->input('game_id');
        $yellowcard2->player_id = $request->input('player_id');
        $yellowcard2->minute = $request->input('minute');
        $yellowcard2->team_id = $request->input('team');
        $yellowcard2->league_id = $request->input('league_id');
        $yellowcard2->season_id = $request->input('season_id');
        $yellowcard2->message = $request->input('message'); // Odczytaj wartość pola 'message'
        $yellowcard2->save();
    }

    private function storeMissedPenalty(Request $request)
    {
        $missedpenalty = new MissedPenalty();
        $missedpenalty->game_id = $request->input('game_id');
        $missedpenalty->player_id = $request->input('player_id');
        $missedpenalty->minute = $request->input('minute');
        $missedpenalty->team_id = $request->input('team');
        $missedpenalty->league_id = $request->input('league_id');
        $missedpenalty->season_id = $request->input('season_id');
        $missedpenalty->message = $request->input('message'); // Odczytaj wartość pola 'message'
        $missedpenalty->save();

    }

    private function updateGameResults($game)
    {
        // Zlicz bramki samobójcze dla obu drużyn
        $team1SuicideGoalsCount = SuicideGoal::where('team_id', $game->team2_id)
            ->where('game_id', $game->id)
            ->count();

        $team2SuicideGoalsCount = SuicideGoal::where('team_id', $game->team1_id)
            ->where('game_id', $game->id)
            ->count();

        // Zlicz bramki dla obu drużyn
        $team1GoalsCount = Goals::where('team_id', $game->team1_id)
            ->where('game_id', $game->id)
            ->count();

        $team2GoalsCount = Goals::where('team_id', $game->team2_id)
            ->where('game_id', $game->id)
            ->count();

        // Aktualizuj wynik w tabeli "games" na podstawie bramek i bramek samobójczych
        $game->result1 = $team1GoalsCount + $team1SuicideGoalsCount;
        $game->result2 = $team2GoalsCount + $team2SuicideGoalsCount;
        $game->save();
    }

    public function storeSuicideGoals(Request $request)
    {
        $suicidegoals = new SuicideGoal();
        $suicidegoals->game_id = $request->input('game_id');
        $suicidegoals->player_id = $request->input('player_id');
        $suicidegoals->minute = $request->input('minute');
        $suicidegoals->team_id = $request->input('team');
        $suicidegoals->league_id = $request->input('league_id');
        $suicidegoals->season_id = $request->input('season_id');
        $suicidegoals->message = $request->input('message'); // Odczytaj wartość pola 'message'
        $suicidegoals->save();

        // Znajdź mecz powiązany z bramką samobójczą
        $game = Game::find($suicidegoals->game_id);

        if ($game) {
            $this->updateGameResults($game);
        }
    }

    public function storeGoalOption2(Request $request)
    {
        $goal = new Goals();
        $goal->game_id = $request->input('game_id');
        $goal->player_id = $request->input('player_id');
        $goal->minute = $request->input('minute');
        $goal->team_id = $request->input('team');
        $goal->league_id = $request->input('league_id');
        $goal->season_id = $request->input('season_id');
        $goal->message = $request->input('message'); // Odczytaj wartość pola 'message'
        $goal->save();

        // Znajdź mecz powiązany z bramką
        $game = Game::find($request->input('game_id'));


        if ($game) {
            $this->updateGameResults($game);
        }
    }


    public function storeChange(Request $request)
    {
        $change = new Change();
        $change->game_id = $request->input('game_id');
        $change->player_id = $request->input('player_id');
        $change->minute = $request->input('minute');
        $change->team_id = $request->input('team');
        $change->league_id = $request->input('league_id');
        $change->season_id = $request->input('season_id');
        $change->message = $request->input('message'); // Odczytaj wartość pola 'message'
        $change->save();

        // Znajdź mecz powiązany z bramką
        $game = Game::find($change->game_id);

        if ($game) {
            $this->updateGameResults($game);
        }
    }


    public function storeInformation(Request $request)
    {
        $information = new Information();
        $information->game_id = $request->input('game_id');
        $information->league_id = $request->input('league_id');
        $information->season_id = $request->input('season_id');
        $information->message = $request->input('message'); // Odczytaj wartość pola 'message'
        $information->save();

        // Znajdź mecz powiązany z bramką
        $game = Game::find($information->game_id);

        if ($game) {
            $this->updateGameResults($game);
        }
    }

}
