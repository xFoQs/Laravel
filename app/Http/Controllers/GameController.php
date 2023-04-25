<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;
use DB;

class GameController extends Controller
{
    public function index()
    {
        $games = DB::table('games')
            ->join('clubs', 'games.team1_id', '=', 'clubs.id')
            ->select('games.*', 'clubs.club_name as club_name')
            ->get();

        $games2 = DB::table('games')
            ->join('clubs', 'games.team2_id', '=', 'clubs.id')
            ->select('games.*', 'clubs.club_name as club_name')
            ->get();

        $games_union= $games->merge($games2);


        $clubs = Club::all();

        return view('Admin.teams' ,['games' => $games_union, 'clubs' => $clubs]);
    }
}
