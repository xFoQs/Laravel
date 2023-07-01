<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StandingController extends Controller
{
    public function index()
    {
        $leagueName = "Klasa okręgowa podkarpacka";

        $teams = Team::whereHas('league', function ($query) use ($leagueName) {
            $query->where('name', $leagueName);
        })->with('league')->get()->sortByDesc('points');

        return view('test2', compact('teams'));
    }


}
