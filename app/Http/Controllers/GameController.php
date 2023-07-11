<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\League;
use App\Models\Season;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GameController extends Controller
{
    public function index(Request $request){


        $leagues = League::all();
        $seasons = Season::orderBy('id', 'desc')->get();

        $selectedLeagueId = $request->input('league', $leagues->first()->id);
        $selectedSeasonId = $request->input('season', $seasons->max('id'));

        $selectedLeague = League::find($selectedLeagueId);
        $selectedSeason = Season::find($selectedSeasonId);

        $games = Game::where('league_id', $selectedLeagueId)
            ->where('season_id', $selectedSeasonId)
            ->get();

        return view('test', compact('games', 'selectedSeason','selectedSeasonId','selectedLeagueId','selectedLeague','leagues','seasons'));
    }
}
