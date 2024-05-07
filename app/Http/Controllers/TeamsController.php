<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Team;
use App\Models\User;

class TeamsController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(Auth::user()->getAuthIdentifier());
        $teams = $user->teams();

        if ($request->has('search')) {
            $teams->where('name', 'like', '%'.$request->input('search').'%');
        }

        $teams = $teams->latest()->paginate(10);

        $googleMapsApiKey = env('GOOGLE_MAP_KEY');

        return view('teams.index', compact('teams', 'googleMapsApiKey'));
    }

    public function show($id)
    {
        $team = Team::findOrFail($id);

        // reikia istestuoti
        if (!Auth::user()->teams->contains($team)) {
            abort(403); 
        }

        return view('teams.show', compact('team'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'team_name' => 'required|string|max:255|unique:teams,name',
        ]);

        $team = Team::create([
            'name' => $validatedData['team_name'],
        ]);

        $user = auth()->user();
        $team->users()->attach($user->id, ['is_admin' => true]);

        return redirect()->back()->with('success', 'Team created successfully.');
    }

    public function guest(Request $request)
    {
        $query = Team::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%'.$request->input('search').'%');
        }

        $teams = $query->paginate(10);

        
        $googleMapsApiKey = env('GOOGLE_MAP_KEY');

        return view('teams.guest', compact('teams', 'googleMapsApiKey'));
    }
}
