<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\Club;
use DB;

class PlayersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $players = DB::table('players')
                    ->join('clubs', 'players.id_club', '=', 'clubs.id')
                    ->select('players.*', 'clubs.club_name as club_name')
                    ->get();
        $clubs = Club::all();
        return view('Admin.players' ,['players' => $players, 'clubs' => $clubs]);
    }


    public function create()
    {
        return view('players.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $players = new Player();
        $players->name = $request->input('name');
        $players->surname = $request->input('surname');
        $players->birthday = $request->input('birthday');
        $players->position = $request->input('position');
        $clubName = $request->input('club');
        $club = Club::where('club_name', $clubName)->first();
        $clubId = $club ? $club->id : null;
        $players->id_club = $clubId;
        $players->save();

        return redirect()->intended('players')->withSuccess('Rekord został dodany pomyślnie');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

    $players = Player::find($request->id);

    $players->name = $request->input('e_name');
    $players->surname = $request->input('e_surname');
    $players->birthday = $request->input('e_birthday');
    $players->position = $request->input('e_position');
    $clubName = $request->input('e_club');
    $club = Club::where('club_name', $clubName)->first();
    $clubId = $club ? $club->id : null;
    $players->id_club = $clubId;

    $players->save();

    return redirect()->intended('players')->withUpdate('Rekord został dodany pomyślnie');

    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Request $id)
    {
        $players = Player::find($id->event_id);
        $players->delete();

        return $players;
    }
}
