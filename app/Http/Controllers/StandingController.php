<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Season;
use App\Models\Game;
use App\Models\League;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class StandingController extends Controller
{
    public function index(Request $request)
    {
        $leagues = League::all();
        $seasons = Season::orderBy('id', 'desc')->get();

        $selectedLeagueId = $request->input('league', $leagues->first()->id);
        $selectedSeasonId = $request->input('season', $seasons->max('id'));

        $selectedLeague = League::find($selectedLeagueId);
        $selectedSeason = Season::find($selectedSeasonId);

        $allGames = Game::where('league_id', $selectedLeagueId)
            ->where('season_id', $selectedSeasonId)
            ->get();

        $homeGames = $allGames->filter(function ($game) {
            return $game->team1_id == $game->home_team_id;
        });

        $awayGames = $allGames->filter(function ($game) {
            return $game->team2_id == $game->home_team_id;
        });

        $teams = Team::where(function ($query) use ($selectedLeagueId, $selectedSeasonId) {
            $query->whereExists(function ($subQuery) use ($selectedLeagueId, $selectedSeasonId) {
                $subQuery->select(DB::raw(1))
                    ->from('games')
                    ->whereRaw('(teams.id = games.team1_id OR teams.id = games.team2_id)')
                    ->where('games.league_id', $selectedLeagueId)
                    ->where('games.season_id', $selectedSeasonId);
            });
        })
            ->get()
            ->sortByDesc(function ($team) use ($selectedSeasonId) {
                return $team->getPointsAttribute($selectedSeasonId);
            });

        $homeTeams = Team::whereExists(function ($query) use ($selectedLeagueId, $selectedSeasonId) {
            $query->select(DB::raw(1))
                ->from('games')
                ->whereRaw('(teams.id = games.team1_id)')
                ->where('games.league_id', $selectedLeagueId)
                ->where('games.season_id', $selectedSeasonId);
        })
            ->get()
            ->sortByDesc(function ($team) use ($selectedSeasonId) {
                return $team->getHomePointsAttribute($selectedSeasonId);
            });

        $awayTeams = Team::whereExists(function ($query) use ($selectedLeagueId, $selectedSeasonId) {
            $query->select(DB::raw(1))
                ->from('games')
                ->whereRaw('(teams.id = games.team2_id)')
                ->where('games.league_id', $selectedLeagueId)
                ->where('games.season_id', $selectedSeasonId);
        })
            ->get()
            ->sortByDesc(function ($team) use ($selectedSeasonId) {
                return $team->getAwayPointsAttribute($selectedSeasonId);
            });

        return view('test2', [
            'teams' => $teams,
            'allGames' => $allGames,
            'homeGames' => $homeGames,
            'awayGames' => $awayGames,
            'homeTeams' => $homeTeams,
            'awayTeams' => $awayTeams,
            'seasons' => $seasons,
            'selectedSeasonId' => $selectedSeasonId,
            'leagues' => $leagues,
            'selectedLeagueId' => $selectedLeagueId,
            'selectedLeague' => $selectedLeague,
            'selectedSeason' => $selectedSeason,
        ]);
    }


}
