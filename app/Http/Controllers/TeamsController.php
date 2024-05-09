<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Team;
use App\Models\User;
use App\Models\Unit;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

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

        // Ensure the logged-in user is associated with the team
        if (!Auth::user()->teams->contains($team)) {
            abort(403); 
        }

        $totalType0 = DB::table('deposits')
                        ->where('team_id', $id)
                        ->where('type', 0)
                        ->sum('count');

        $countType0 = DB::table('deposits')
                            ->where('team_id', $id)
                            ->where('type', 0)
                            ->count();

        $countType1 = DB::table('deposits')
                        ->where('team_id', $id)
                        ->where('type', 1)
                        ->count();


        $totalType1 = Unit::whereIn('deposit_id', function($query) use ($id) {
            $query->select('id')
                  ->from('deposits')
                  ->where('team_id', $id)
                  ->where('type', 1);
        })->count();

        $teamDepositTags = Tag::whereIn('id', function($query) use ($id) {
            $query->select('tag_id')
                  ->from('deposit_tag')
                  ->whereIn('deposit_id', function($subquery) use ($id) {
                        $subquery->select('id')
                                 ->from('deposits')
                                 ->where('team_id', $id);
                  });
        })->get();

        $teamDepositTagsWithCount = Tag::whereIn('id', function($query) use ($id) {
            $query->select('tag_id')
                  ->from('deposit_tag')
                  ->whereIn('deposit_id', function($subquery) use ($id) {
                        $subquery->select('id')
                                 ->from('deposits')
                                 ->where('team_id', $id);
                  });
        })->withCount('deposit')->get();

        return view('teams.show', compact('team', 'totalType0', 'countType0', 'countType1', 'totalType1', 'teamDepositTags', 'teamDepositTagsWithCount'));
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

    public function showGuest($id)
    {
        $team = Team::findOrFail($id);

        $totalType0 = DB::table('deposits')
                        ->where('team_id', $id)
                        ->where('type', 0)
                        ->sum('count');

        $countType0 = DB::table('deposits')
                            ->where('team_id', $id)
                            ->where('type', 0)
                            ->count();

        $countType1 = DB::table('deposits')
                        ->where('team_id', $id)
                        ->where('type', 1)
                        ->count();


        $totalType1 = Unit::whereIn('deposit_id', function($query) use ($id) {
            $query->select('id')
                  ->from('deposits')
                  ->where('team_id', $id)
                  ->where('type', 1);
        })->count();

        $teamDepositTags = Tag::whereIn('id', function($query) use ($id) {
            $query->select('tag_id')
                  ->from('deposit_tag')
                  ->whereIn('deposit_id', function($subquery) use ($id) {
                        $subquery->select('id')
                                 ->from('deposits')
                                 ->where('team_id', $id);
                  });
        })->get();

        $teamDepositTagsWithCount = Tag::whereIn('id', function($query) use ($id) {
            $query->select('tag_id')
                  ->from('deposit_tag')
                  ->whereIn('deposit_id', function($subquery) use ($id) {
                        $subquery->select('id')
                                 ->from('deposits')
                                 ->where('team_id', $id);
                  });
        })->withCount('deposit')->get();

        return view('teams.showGuest', compact('team', 'totalType0', 'countType0', 'countType1', 'totalType1', 'teamDepositTags', 'teamDepositTagsWithCount'));
    }
}
