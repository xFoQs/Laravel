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

class UserController extends Controller
{

    public function index(): View
    {
        $users = User::all();

        return view('admin.users', compact('users'));
    }


    public function updateRole(Request $request)
    {
        // Validate the request data as needed
        $request->validate([
            'role' => 'required|in:1,2', // Assuming roles 1 and 2 for Administrator and Pracownik
        ]);

        // Find the user by ID
        $user = User::findOrFail($request->id);

        // Update the user's role
        $user->update([
            'role_id' => $request->role,
        ]);

        // Redirect or respond as needed
        return redirect()->back()->with('Brawo', 'Rola została zaaktualizowana');
    }


    public function destroy(Request $id)
    {
        $users = User::find($id->event_id);
        $users->delete();

        return $users;
    }

}
