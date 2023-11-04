<?php

namespace App\Http\Controllers\Admin;

use App\Models\Season;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SeasonController extends Controller
{
    public function index()
    {
        $seasons = Season::all();
        return view('Admin.seasons', compact('seasons'));
    }


    public function store(Request $request)
    {
        $season = new Season();
        $season->name = $request->input('name');

        $season->save();

        return redirect()->intended('seasons')->withSuccess('Rekord został dodany pomyślnie');
    }

    public function destroy(Request $id)
    {
        $season = Season::find($id->event_id);
        $season->delete();

        return $season;
    }

    public function update(Request $request)
    {

        $season = Season::findOrFail($request->id);

        $season->name = $request->input('name');

        $season->save();

        return redirect()->intended('seasons')->withUpdate('Rekord został zaktualizowany pomyślnie');

    }
}
