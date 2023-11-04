<?php

namespace App\Http\Controllers\Admin;

use App\Models\League;
use App\Models\Season;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeagueController extends Controller
{
    public function index()
    {
        $leagues = League::all();
        return view('Admin.leagues', compact('leagues'));
    }


    public function store(Request $request)
    {
        $leagues = new League();
        $leagues->name = $request->input('name');

        $leagues->save();

        return redirect()->intended('leagues')->withSuccess('Rekord został dodany pomyślnie');
    }

    public function destroy(Request $id)
    {
        $league = League::find($id->event_id);
        $league->delete();

        return $league;
    }

    public function update(Request $request)
    {

        $leagues = League::findOrFail($request->id);

        $leagues->name = $request->input('name');

        $leagues->save();

        return redirect()->intended('leagues')->withUpdate('Rekord został zaktualizowany pomyślnie');

    }
}
