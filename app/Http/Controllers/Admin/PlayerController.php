<?php

namespace App\Http\Controllers\Admin;

use App\Models\Player;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Models\Team;
use DB;
use Illuminate\Http\Request;

class PlayerController extends Controller
{

    public function index(): View
    {
        $players = DB::table('players')
            ->join('teams', 'players.team_id', '=', 'teams.id')
            ->select('players.*', 'teams.name as team_name')
            ->get();
        $teams = Team::all();
        return view('Admin.players' ,['players' => $players, 'teams' => $teams]);
    }


    public function store(Request $request)
    {
        $players = new Player();
        $players->name = $request->input('name');
        $players->surname = $request->input('surname');
        $players->birth_date = $request->input('birth_date');
        $players->position = $request->input('position');
        $teamName = $request->input('team_id');
        $team = Team::where('name', $teamName)->first();
        $teamId = $team ? $team->id : null;
        $players->team_id = $teamId;
        $players->save();

        return redirect()->intended('players')->withSuccess('Rekord został dodany pomyślnie');
    }

    public function show(Player $player): View
    {
        return view('admin.players', compact('player'));
    }


    public function update(Request $request)
    {

        $players = Player::findOrFail($request->id);

        $players->name = $request->input('name');
        $players->surname = $request->input('surname');
        $players->birth_date = $request->input('birth_date');
        $players->position = $request->input('position');
        $teamName = $request->input('team_id');
        $team = Team::where('name', $teamName)->first();
        $teamId = $team ? $team->id : null;
        $players->team_id = $teamId;

        $players->save();

        return redirect()->intended('players')->withUpdate('Rekord został dodany pomyślnie');

    }

    public function destroy(Request $id)
    {
        $players = Player::find($id->event_id);
        $players->delete();

        return $players;
    }

    public function massDestroy()
    {
        Player::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }
}
