<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\League;
use App\Models\Player;
use App\Models\Season;
use App\Models\Goals;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index(Request $request)
    {
        $leagues = League::all();
        $seasons = Season::all();

        $selectedLeagueId = $request->input('league', $leagues->first()->id);
        $selectedSeasonId = $request->input('season', $seasons->max('id'));

        $players = Player::whereHas('goals.game', function ($query) use ($selectedLeagueId, $selectedSeasonId) {
            $query->where('league_id', $selectedLeagueId)
                ->where('season_id', $selectedSeasonId);
        })->with(['goals' => function ($query) use ($selectedLeagueId, $selectedSeasonId) {
            $query->whereHas('game', function ($query) use ($selectedLeagueId, $selectedSeasonId) {
                $query->where('league_id', $selectedLeagueId)
                    ->where('season_id', $selectedSeasonId);
            });
        }])->get();

        // Ustawienie wartości zero dla zawodników bez wpisów
        $players->each(function ($player) {
            if ($player->goals->isEmpty()) {
                $player->goals_count = 0;
            } else {
                $player->goals_count = $player->goals->count();
            }
        });

        // Sortowanie malejące po liczbie strzelonych goli
        $players = $players->sortByDesc(function ($player) {
            return $player->goals_count;
        });

        return view('test3', compact('players', 'selectedLeagueId', 'selectedSeasonId', 'leagues', 'seasons'));
    }


}
