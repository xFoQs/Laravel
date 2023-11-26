<?php

namespace App\Http\Controllers\Admin;

use App\Models\Game;
use App\Models\League;
use App\Models\Season;
use App\Models\Team;
use App\Models\User;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Admin\TeamRequest;
use Illuminate\Http\Request;

class TeamController extends Controller
{

    public function index(): View
    {
        $teams = Team::all();
        $leagues = League::all();
        $users = User::all();
        $seasons = Season::all();

        return view('admin.teams', compact('teams','leagues','seasons','users'));
    }

    public function create(): View
    {
        return view('admin.teams.create');
    }

    public function store(TeamRequest $request): RedirectResponse
    {
        $team = new Team();
        $team->name = $request->input('name');
        $team->league_id = $request->input('league_id');

        if ($request->hasFile('customFile')) {
            $file = $request->file('customFile');
            $extension = $file->getClientOriginalExtension();
            $fileName = uniqid() . '.' . $extension;
            $file->move(public_path('img'), $fileName);
            $team->photo = $fileName;
        }

        $team->save();

        return redirect()->intended('teams')->withSuccess('Rekord został dodany pomyślnie');
    }

    public function show(Team $team): View
    {
        $games = Game::where('team1_id' , $team->id)->orWhere('team2_id', $team->id)->get();

        return view('admin.teams.show', compact('team', 'games'));
    }

    public function edit(Team $team): View
    {
        return view('admin.teams.edit', compact('team'));
    }

    public function update(Request $request)
    {
        $team = Team::findOrFail($request->id);

        $team->name = $request->input('name');
        $team->league_id = $request->input('league_id');

        if ($request->hasFile('customFile')) {
            // Usuń poprzednie zdjęcie, jeśli istnieje
            $existingImage = $team->photo;
            if ($existingImage) {
                $imagePath = public_path('img') . '/' . $existingImage;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Zapisz nowe zdjęcie
            $image = $request->file('customFile');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('img'), $imageName);
            $team->photo = $imageName;
        } elseif ($request->has('deletePhoto')) {
            // Usuń zdjęcie, jeśli użytkownik zaznaczył opcję "Usuń zdjęcie"
            $existingImage = $team->photo;
            if ($existingImage) {
                $imagePath = public_path('img') . '/' . $existingImage;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $team->photo = null;
            }
        }

        $team->save();

        return redirect()->intended('teams')->withUpdate('Rekord został zaktualizowany pomyślnie');
    }

    public function destroy(Request $id)
    {
        $teams = Team::find($id->event_id);
        $teams->delete();

        return $teams;
    }

    public function massDestroy()
    {
        Team::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }
}
