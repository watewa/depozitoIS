<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\Team;
use App\Models\User;
use App\Models\Unit;
use App\Models\Deposit;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TeamsController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(Auth::id());
        $teams = $user->teams()->wherePivot('is_admin', '<=', 1);
        $invites = $user->teams()->wherePivot('is_admin', '=', 2);

        if ($request->has('search')) {
            $teams->where('name', 'like', '%'.$request->input('search').'%');
            $invites->where('name', 'like', '%'.$request->input('search').'%');
        }

        $teams = $teams->latest()->paginate(10);
        $invites = $invites->latest()->paginate(10);

        return view('teams.index', compact('teams', 'invites'));
    }

    public function show($id)
    {
        $team = Team::findOrFail($id);

        if (!Auth::user()->teams->contains($team) || $team->users->where('id', Auth::id())->first()->pivot->is_admin == 2) {
            abort(403); 
        }

        $totalType0 = DB::table('deposits')
                        ->join('deposit_team', 'deposits.id', '=', 'deposit_team.deposit_id')
                        ->where('deposit_team.team_id', $id)
                        ->where('deposits.type', 0)
                        ->sum('deposits.countP');

        $countType0 = DB::table('deposits')
                        ->join('deposit_team', 'deposits.id', '=', 'deposit_team.deposit_id')
                        ->where('deposit_team.team_id', $id)
                        ->where('deposits.type', 0)
                        ->count();

        $countType1 = DB::table('deposits')
                        ->join('deposit_team', 'deposits.id', '=', 'deposit_team.deposit_id')
                        ->where('deposit_team.team_id', $id)
                        ->where('deposits.type', 1)
                        ->count();


        $totalType1 = Unit::whereIn('deposit_id', function($query) use ($id) {
            $query->select('deposits.id')
                    ->from('deposits')
                    ->join('deposit_team', 'deposits.id', '=', 'deposit_team.deposit_id')
                    ->where('deposit_team.team_id', $id)
                    ->where('deposits.type', 1);
        })->count();

        $teamDepositTags = Tag::whereIn('id', function($query) use ($id) {
            $query->select('tag_id')
                  ->from('deposit_tag')
                  ->whereIn('deposit_id', function($subquery) use ($id) {
                      $subquery->select('deposits.id')
                               ->from('deposits')
                               ->join('deposit_team', 'deposits.id', '=', 'deposit_team.deposit_id')
                               ->where('deposit_team.team_id', $id);
                  });
        })->get();
    
        $teamDepositTagsWithCount = Tag::whereIn('id', function($query) use ($id) {
            $query->select('tag_id')
                  ->from('deposit_tag')
                  ->whereIn('deposit_id', function($subquery) use ($id) {
                      $subquery->select('deposits.id')
                               ->from('deposits')
                               ->join('deposit_team', 'deposits.id', '=', 'deposit_team.deposit_id')
                               ->where('deposit_team.team_id', $id);
                  });
        })->withCount('deposit')->get();

        // Prepare data for Chart.js
        $labels = [];
        $counts = [];

        foreach ($teamDepositTagsWithCount as $tag) {
            $labels[] = $tag->name;
            $counts[] = $tag->deposit_count;
            $colors[] = $tag->color; // Assuming 'color' is the field storing the colors
        }
    
        // Add 'Untagged' label and count to data
        $labels[] = 'Nežymėta';
        $counts[] = Deposit::doesntHave('tags')
            ->join('deposit_team', 'deposits.id', '=', 'deposit_team.deposit_id')
            ->where('deposit_team.team_id', $id)
            ->count();
        $colors[] = '#CCCCCC';
        return view('teams.show', compact('labels', 'counts', 'colors', 'team', 'totalType0', 'countType0', 'countType1', 'totalType1', 'teamDepositTags', 'teamDepositTagsWithCount'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'team_name' => 'required|string|max:255|unique:teams,name',
            'location' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'house_nr' => 'required|string|max:255',
            'zip_code' => 'required|string|max:255',
            'extra_line' => 'nullable|string|max:255',
            'picture' => 'nullable|file|mimes:jpeg,jpg,png|max:2048',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|string|max:255',
            'other' => 'nullable|string|max:255',
        ]);

        $picturePath = null;
        if ($request->hasFile('picture')) {
            $picturePath = $request->file('picture')->storeAs(
                'team_pictures',
                uniqid() . '.' . $request->file('picture')->getClientOriginalExtension(),
                'public'
            );
        }
    
        $team = Team::create([
            'name' => $validatedData['team_name'],
            'location' => $validatedData['latitude'].", ".$validatedData['longitude'],
            'city' => $validatedData['city'],
            'street' => $validatedData['street'],
            'house_nr' => $validatedData['house_nr'],
            'zip_code' => $validatedData['zip_code'],
            'extra_line' => $validatedData['extra_line'],
            'picture' => $picturePath,
            'phone' => $validatedData['phone'],
            'email' => $validatedData['email'],
            'other' => $validatedData['other'],
        ]);

        $user = auth()->user();
        $team->users()->attach($user->id, ['is_admin' => true]);

        return redirect()->back()->with('success', 'Įstaiga sukurta sėkmingai.');
    }

    public function guest(Request $request)
    {
        $query = Team::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%'.$request->input('search').'%');
        }

        $teams = $query->paginate(5);

        return view('teams.guest', compact('teams'));
    }

    public function showGuest($id)
    {
        $team = Team::findOrFail($id);

        $totalType0 = DB::table('deposits')
                        ->join('deposit_team', 'deposits.id', '=', 'deposit_team.deposit_id')
                        ->where('deposit_team.team_id', $id)
                        ->where('deposits.type', 0)
                        ->sum('deposits.countP');

        $countType0 = DB::table('deposits')
                        ->join('deposit_team', 'deposits.id', '=', 'deposit_team.deposit_id')
                        ->where('deposit_team.team_id', $id)
                        ->where('deposits.type', 0)
                        ->count();

        $countType1 = DB::table('deposits')
                        ->join('deposit_team', 'deposits.id', '=', 'deposit_team.deposit_id')
                        ->where('deposit_team.team_id', $id)
                        ->where('deposits.type', 1)
                        ->count();


        $totalType1 = Unit::whereIn('deposit_id', function($query) use ($id) {
            $query->select('deposits.id')
                    ->from('deposits')
                    ->join('deposit_team', 'deposits.id', '=', 'deposit_team.deposit_id')
                    ->where('deposit_team.team_id', $id)
                    ->where('deposits.type', 1);
        })->count();

        $teamDepositTags = Tag::whereIn('id', function($query) use ($id) {
            $query->select('tag_id')
                  ->from('deposit_tag')
                  ->whereIn('deposit_id', function($subquery) use ($id) {
                      $subquery->select('deposits.id')
                               ->from('deposits')
                               ->join('deposit_team', 'deposits.id', '=', 'deposit_team.deposit_id')
                               ->where('deposit_team.team_id', $id);
                  });
        })->get();
    
        $teamDepositTagsWithCount = Tag::whereIn('id', function($query) use ($id) {
            $query->select('tag_id')
                  ->from('deposit_tag')
                  ->whereIn('deposit_id', function($subquery) use ($id) {
                      $subquery->select('deposits.id')
                               ->from('deposits')
                               ->join('deposit_team', 'deposits.id', '=', 'deposit_team.deposit_id')
                               ->where('deposit_team.team_id', $id);
                  });
        })->withCount('deposit')->get();

        // Prepare data for Chart.js
        $labels = [];
        $counts = [];

        foreach ($teamDepositTagsWithCount as $tag) {
            $labels[] = $tag->name;
            $counts[] = $tag->deposit_count;
            $colors[] = $tag->color; // Assuming 'color' is the field storing the colors
        }
    
        // Add 'Untagged' label and count to data
        $labels[] = 'Nežymėta';
        $counts[] = Deposit::doesntHave('tags')
            ->join('deposit_team', 'deposits.id', '=', 'deposit_team.deposit_id')
            ->where('deposit_team.team_id', $id)
            ->count();
        $colors[] = '#CCCCCC';
        
        return view('teams.showGuest', compact('labels', 'counts', 'colors', 'team', 'totalType0', 'countType0', 'countType1', 'totalType1', 'teamDepositTags', 'teamDepositTagsWithCount'));
    }

    public function update($id)
    { 
        $team = Team::findOrFail($id);

        $isAdmin = $team->users()
                    ->where('user_id', Auth::id())
                    ->wherePivot('is_admin', 1)
                    ->exists();

        if (!$isAdmin) {
            abort(403, 'Unauthorized action.');
        }

        return view('teams.edit', compact('team'));
    }

    public function updateName(Request $request, $id)
    {
        $team = Team::findOrFail($id);

        $isAdmin = $team->users()
                    ->where('user_id', Auth::id())
                    ->wherePivot('is_admin', 1)
                    ->exists();

        if (!$isAdmin) {
            abort(403, 'Unauthorized action.');
        }

        $validatedData = $request->validate([
            'team_name' => 'required|string|max:255|unique:teams,name',
        ]);

        $team->update(['name' => $validatedData['team_name']]);

        return redirect()->route('teams.edit', ['id' => $team->id])->with('success', 'Įstaigos pavadinimas sėkmingai pakeistas.');
    }

    public function updatePicture(Request $request, $id)
    {
        $request->validate([
            'picture' => 'required|file|mimes:jpeg,jpg,png|max:2048',
        ]);

        $team = Team::findOrFail($id);

        $isAdmin = $team->users()
                    ->where('user_id', Auth::id())
                    ->wherePivot('is_admin', 1)
                    ->exists();

        if (!$isAdmin) {
            abort(403, 'Unauthorized action.');
        }

        $teamPictureOld = $team->picture;

        if ($teamPictureOld) {
            Storage::disk('public')->delete($teamPictureOld);
        }

        $picturePath = null;
        if ($request->hasFile('picture')) {
            $picturePath = $request->file('picture')->storeAs(
                'team_pictures',
                uniqid() . '.' . $request->file('picture')->getClientOriginalExtension(),
                'public'
            );
            $team->update(['picture' => $picturePath]);
        }

        return redirect()->route('teams.edit', ['id' => $team->id])->with('success', 'Įstaigos profilio nuotrauka sėkmingai pakeista.');
    }

    protected function deletePicture($id)
    {
        $team = Team::findOrFail($id);

        $isAdmin = $team->users()
                    ->where('user_id', Auth::id())
                    ->wherePivot('is_admin', 1)
                    ->exists();

        if (!$isAdmin) {
            abort(403, 'Unauthorized action.');
        }

        $currentPicture = $team->picture;

        if ($currentPicture) {
            Storage::disk('public')->delete($currentPicture);
        }

        $team->update(['picture' => null]);

        return redirect()->route('teams.edit', ['id' => $team->id])->with('success', 'Įstaigos profilio nuotrauka sėkmingai pašalinta.');
    }

    public function destroy($id)
    {
        $team = Team::findOrFail($id);

        $isAdmin = $team->users()
                    ->where('user_id', Auth::id())
                    ->wherePivot('is_admin', 1)
                    ->exists();

        if (!$isAdmin) {
            abort(403, 'Unauthorized action.');
        }

        $team->delete();

        return redirect()->route('teams.index');
    }

    public function updateAddress(Request $request, $id)
    {
        $team = Team::findOrFail($id);

        $isAdmin = $team->users()
                    ->where('user_id', Auth::id())
                    ->wherePivot('is_admin', 1)
                    ->exists();

        if (!$isAdmin) {
            abort(403, 'Unauthorized action.');
        }

        $validatedData = $request->validate([
            'location' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'house_nr' => 'required|string|max:255',
            'zip_code' => 'required|string|max:255',
            'extra_line' => 'nullable|string|max:255',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
        ]);
    
        $team->update([
            'location' => $validatedData['latitude'].", ".$validatedData['longitude'],
            'city' => $validatedData['city'],
            'street' => $validatedData['street'],
            'house_nr' => $validatedData['house_nr'],
            'zip_code' => $validatedData['zip_code'],
            'extra_line' => $validatedData['extra_line'],
        ]);

        return redirect()->route('teams.edit', ['id' => $team->id])->with('success', 'Įstaigos adresas sėkmingai pakeistas.');
    }

    public function updateContacts(Request $request, $id)
    {
        $team = Team::findOrFail($id);

        $isAdmin = $team->users()
                    ->where('user_id', Auth::id())
                    ->wherePivot('is_admin', 1)
                    ->exists();

        if (!$isAdmin) {
            abort(403, 'Unauthorized action.');
        }

        $validatedData = $request->validate([
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|string|max:255',
            'other' => 'nullable|string|max:255',
        ]);
    
        $team->update([
            'phone' => $validatedData['phone'],
            'email' => $validatedData['email'],
            'other' => $validatedData['other'],
        ]);

        return redirect()->route('teams.edit', ['id' => $team->id])->with('success', 'Įstaigos kontaktai sėkmingai pakeisti.');
    }

    public function updateMember(Request $request, $id)
    {
        $team = Team::findOrFail($id);

        $isAdmin = $team->users()
                    ->where('user_id', Auth::id())
                    ->wherePivot('is_admin', 1)
                    ->exists();

        if (!$isAdmin) {
            abort(403, 'Unauthorized action.');
        }

        $userId = $request->input('user_id');

        if (!$userId) {
            return redirect()->route('teams.edit', ['id' => $team->id])->with('failed', 'Vartotojas nerastas.');
        }

        $user = User::findOrFail($userId);

        $isMember = $team->users()->where('user_id', $userId)->exists();
        if ($isMember) {
            return redirect()->route('teams.edit', ['id' => $team->id])->with('failed', 'Negalima pridėti jau įstaigai priklausančio nario.');
        }

        $team->users()->attach($user->id, ['is_admin' => 2]);

        return redirect()->route('teams.edit', ['id' => $team->id])->with('success', 'Įstaigos narys sėkmingai pridėtas.');
    }

    public function removeMember($id, $uid)
    {
        $team = Team::findOrFail($id);

        $isAdmin = $team->users()
                    ->where('user_id', Auth::id())
                    ->wherePivot('is_admin', 1)
                    ->exists();

        if (!$isAdmin) {
            abort(403, 'Unauthorized action.');
        }

        $user = $team->users()->findOrFail($uid);

        if ($user->pivot->is_admin == 1) {
            $adminCount = $team->users()->wherePivot('is_admin', 1)->count();
            if ($adminCount <= 1) {
                return redirect()->back()->with('failed', 'Negalima pašalinti paskutinio administratoriaus.');
            }
        }

        $team->users()->detach($uid);

        return redirect()->back()->with('success', 'Vartotojas buvo sėkmingai pašalintas iš komandos.');
    }

    public function updateMemberRole(Request $request, $teamId, $userId)
    {
        $team = Team::findOrFail($teamId);

        $isAdmin = $team->users()
                    ->where('user_id', Auth::id())
                    ->wherePivot('is_admin', 1)
                    ->exists();

        if (!$isAdmin) {
            abort(403, 'Unauthorized action.');
        }

        $user = $team->users()->findOrFail($userId);
        $newAdminStatus = $request->input('is_admin');

        if (!in_array($newAdminStatus, [0, 1])) {
            return response()->json(['success' => false, 'message' => 'Draudžiama teisių reikšmė.'], 400);
        }

        if ($user->pivot->is_admin == 2) {
            return response()->json(['success' => false, 'message' => 'Negalima keisti pakviesto vartotojo rolės.'], 400);
        }

        if ($user->pivot->is_admin == 1 && $newAdminStatus == 0) {
            $adminCount = $team->users()->wherePivot('is_admin', 1)->count();
            if ($adminCount <= 1) {
                return response()->json(['success' => false, 'message' => 'Įstaigoje turi būti bent vienas administratorius.'], 400);
            }
        }

        $team->users()->updateExistingPivot($userId, ['is_admin' => $newAdminStatus]);

        return response()->json(['success' => true]);
    }

    public function acceptInvite($id)
    {
        $user = Auth::user();

        $invite = $user->teams()->where('team_id', $id)->wherePivot('is_admin', 2)->first();

        if ($invite) {
            $user->teams()->updateExistingPivot($id, ['is_admin' => 0]);

            return redirect()->route('teams.index')->with('success', 'Invitation accepted successfully.');
        } else {
            return redirect()->route('teams.index')->with('error', 'No valid invitation found.');
        }
    }

    public function rejectInvite($id)
    {
        $user = Auth::user();

        $invite = $user->teams()->where('team_id', $id)->wherePivot('is_admin', 2)->first();

        if ($invite) {
            $user->teams()->detach($id);

            return redirect()->route('teams.index')->with('success', 'Invitation rejected successfully.');
        } else {
            return redirect()->route('teams.index')->with('error', 'No valid invitation found.');
        }
    }

    
}
