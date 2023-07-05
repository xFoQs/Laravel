<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\League;
use App\Models\Season;
use App\Models\Goals;
use App\Models\Game;
use App\Models\Team;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $leagues = League::all();
        $seasons = Season::orderBy('id', 'desc')->get();

        $selectedLeagueId = $request->input('league', $leagues->first()->id);
        $selectedSeasonId = $request->input('season', $seasons->max('id'));

        $teams = Team::where(function ($query) use ($selectedLeagueId, $selectedSeasonId) {
            $query->whereExists(function ($subQuery) use ($selectedLeagueId, $selectedSeasonId) {
                $subQuery->select(DB::raw(1))
                    ->from('games')
                    ->whereRaw('(teams.id = games.team1_id OR teams.id = games.team2_id)')
                    ->where('games.league_id', $selectedLeagueId)
                    ->where('games.season_id', $selectedSeasonId);
            });
        })
            ->get();

        $goalsCount = [];

        foreach ($teams as $team) {
            $goalsCount[$team->id] = [
                '1-15' => [
                    'scored' => 0,
                    'conceded' => 0,
                ],
                '16-30' => [
                    'scored' => 0,
                    'conceded' => 0,
                ],
                '31-45' => [
                    'scored' => 0,
                    'conceded' => 0,
                ],
                '46-60' => [
                    'scored' => 0,
                    'conceded' => 0,
                ],
                '61-75' => [
                    'scored' => 0,
                    'conceded' => 0,
                ],
                '76-90' => [
                    'scored' => 0,
                    'conceded' => 0,
                ],
            ];
        }

        $games = Game::where('league_id', $selectedLeagueId)
            ->where('season_id', $selectedSeasonId)
            ->get();

        foreach ($games as $game) {
            $team1 = Team::find($game->team1_id);
            $team2 = Team::find($game->team2_id);

            $goals = Goals::where('game_id', $game->id)->get();

            foreach ($goals as $goal) {
                $minute = $goal->minute;
                $scoringTeam = $goal->team_id;
                $opposingTeam = $team1->id === $scoringTeam ? $team2->id : $team1->id;

                if ($minute >= 1 && $minute <= 15) {
                    $goalsCount[$scoringTeam]['1-15']['scored']++;
                    $goalsCount[$opposingTeam]['1-15']['conceded']++;
                } elseif ($minute >= 16 && $minute <= 30) {
                    $goalsCount[$scoringTeam]['16-30']['scored']++;
                    $goalsCount[$opposingTeam]['16-30']['conceded']++;
                } elseif ($minute >= 31 && $minute <= 45) {
                    $goalsCount[$scoringTeam]['31-45']['scored']++;
                    $goalsCount[$opposingTeam]['31-45']['conceded']++;
                } elseif ($minute >= 46 && $minute <= 60) {
                    $goalsCount[$scoringTeam]['46-60']['scored']++;
                    $goalsCount[$opposingTeam]['46-60']['conceded']++;
                } elseif ($minute >= 61 && $minute <= 75) {
                    $goalsCount[$scoringTeam]['61-75']['scored']++;
                    $goalsCount[$opposingTeam]['61-75']['conceded']++;
                } elseif ($minute >= 76 && $minute <= 130) {
                    $goalsCount[$scoringTeam]['76-90']['scored']++;
                    $goalsCount[$opposingTeam]['76-90']['conceded']++;
                }
            }
        }

        return view('test4', [
            'teams' => $teams,
            'goalsCount' => $goalsCount,
            'seasons' => $seasons,
            'selectedSeasonId' => $selectedSeasonId,
            'leagues' => $leagues,
            'selectedLeagueId' => $selectedLeagueId,
        ]);
    }


}
