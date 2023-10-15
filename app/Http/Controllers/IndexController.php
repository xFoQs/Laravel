<?php


namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Player;

class IndexController extends Controller
{
    public function index() {

        $games = Game::all();
        $players = Player::all();
        return view('page', compact('games', 'players'));
    }


}
