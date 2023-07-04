<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Season;
use App\Models\Game;
use App\Models\League;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class StandingController extends Controller
{
    public function index(Request $request)
    {
        $leagues = League::all();
        $seasons = Season::orderBy('id', 'desc')->get();

        $selectedLeagueId = $request->input('league', $leagues->first()->id);
        $selectedSeasonId = $request->input('season', $seasons->max('id'));

        $teams = Team::whereHas('league', function ($query) use ($selectedLeagueId) {
            $query->where('id', $selectedLeagueId);
        })->with('league')->get();

        $teams = $teams->sortByDesc(function ($team) use ($selectedSeasonId) {
            return $team->getPointsAttribute($selectedSeasonId);
        });

        return view('test2', [
            'teams' => $teams,
            'seasons' => $seasons,
            'selectedSeasonId' => $selectedSeasonId,
            'leagues' => $leagues,
            'selectedLeagueId' => $selectedLeagueId,
        ]);
    }


}
