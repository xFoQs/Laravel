<?php

namespace App\Http\Controllers\Admin;

use App\Models\Player;
use App\Models\Season;
use App\Models\PlayerSeason;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PlayerController extends Controller
{

    public function index(): View
    {
        $players = Player::with('seasons')->get();

        $teams = Team::all();
        $seasons = Season::with('teams')->get(); // Dodane pobranie klubów dla sezonów

        return view('Admin.players', ['players' => $players, 'teams' => $teams, 'seasons' => $seasons]);
    }


    public function store(Request $request)
    {
        $player = new Player();
        $player->name = $request->input('name');
        $player->surname = $request->input('surname');
        $player->birth_date = $request->input('birth_date');
        $player->position = $request->input('position');
        $player->country = $request->input('country');
        $player->save();

        $seasonNames = $request->input('season_name');
        $teamIds = $request->input('season_team_id');

        if ($request->hasFile('customFile')) {
            $file = $request->file('customFile');
            $extension = $file->getClientOriginalExtension();
            $fileName = uniqid() . '.' . $extension;
            $file->move(public_path('img'), $fileName);
            $player->photo = $fileName;
        }

        for ($i = 0; $i < count($seasonNames); $i++) {
            $seasonId = $seasonNames[$i];
            $teamId = $teamIds[$i];

            $player->seasons()->attach($seasonId, ['team_id' => $teamId]);

            // Aktualizacja aktualnego klubu (team_id) dla zawodnika
            $player->team_id = $teamId;
            $player->save();
        }

        return redirect()->intended('players')->withSuccess('Rekord został dodany pomyślnie');
    }

    public function update(Request $request)
    {
        $player = Player::find($request->id);

        if ($player) {
            $player->name = $request->name;
            $player->surname = $request->surname;
            $player->birth_date = $request->birth_date;
            $player->position = $request->position;
            $player->country = $request->country;
            $player->team_id = $request->input('season_team_id.' . $player->id . '.0'); // Pobierz wartość pierwszego elementu z season_team_id

            if ($request->hasFile('customFile')) {
                $image = $request->file('customFile');

                // Usuń poprzednie zdjęcie, jeśli istnieje
                if ($player->photo) {
                    $existingImagePath = public_path('img') . '/' . $player->photo;
                    if (file_exists($existingImagePath)) {
                        unlink($existingImagePath);
                    }
                }

                // Generuj unikalną nazwę pliku
                $imageName = time() . '_' . $image->getClientOriginalName();
                // Możesz również użyć UUID jako unikalnej nazwy pliku:
                // $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();

                // Zapisz nowe zdjęcie
                $image->move(public_path('img'), $imageName);
                $player->photo = $imageName;
            }

            $player->save();

            // Aktualizuj dane kariery zawodnika
            $seasonNames = $request->input('season_name.' . $player->id, []);
            $seasonTeamIds = $request->input('season_team_id.' . $player->id, []);

            // Usuń stare dane kariery zawodnika
            PlayerSeason::where('player_id', $player->id)->delete();

            // Dodaj nowe dane kariery zawodnika
            foreach ($seasonNames as $key => $seasonName) {
                $seasonTeamId = $seasonTeamIds[$key];

                if (!empty($seasonName) && !empty($seasonTeamId)) {
                    PlayerSeason::create([
                        'player_id' => $player->id,
                        'season_id' => $seasonName,
                        'team_id' => $seasonTeamId,
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Dane zawodnika zostały zaktualizowane.');
        }

        return redirect()->back()->with('error', 'Nie znaleziono zawodnika o podanym identyfikatorze.');
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
