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
                '1-15' => 0,
                '16-30' => 0,
                '31-45' => 0,
                '46-60' => 0,
                '61-75' => 0,
                '76-90' => 0,
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

                if ($minute >= 1 && $minute <= 15) {
                    $goalsCount[$team1->id]['1-15']++;
                    $goalsCount[$team2->id]['1-15']++;
                } elseif ($minute >= 16 && $minute <= 30) {
                    $goalsCount[$team1->id]['16-30']++;
                    $goalsCount[$team2->id]['16-30']++;
                } elseif ($minute >= 31 && $minute <= 45) {
                    $goalsCount[$team1->id]['31-45']++;
                    $goalsCount[$team2->id]['31-45']++;
                } elseif ($minute >= 46 && $minute <= 60) {
                    $goalsCount[$team1->id]['46-60']++;
                    $goalsCount[$team2->id]['46-60']++;
                } elseif ($minute >= 61 && $minute <= 75) {
                    $goalsCount[$team1->id]['61-75']++;
                    $goalsCount[$team2->id]['61-75']++;
                } elseif ($minute >= 76 && $minute <= 130) {
                    $goalsCount[$team1->id]['76-90']++;
                    $goalsCount[$team2->id]['76-90']++;
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
