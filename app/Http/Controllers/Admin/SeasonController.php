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
        return view('Admin.players', compact('seasons'));
    }

    public function create()
    {
        return view('seasons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:seasons',
        ]);

        Season::create([
            'name' => $request->input('name'),
        ]);

        return redirect()->route('seasons.index')->with('success', 'Sezon został dodany.');
    }
}
