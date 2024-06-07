<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\Team;
use App\Models\Unit;
use App\Models\Tag;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;

class DepositController extends Controller
{
    public function index($team_id, Request $request)
    {
        $team = Team::find($team_id);
        $tags = Tag::all();

        if (!Auth::user()->teams->contains($team)) {
            abort(403); 
        }

        $search = $request->input('search');

        $depositsPQuery = $team->deposits()->wherePivot('role', 'p');
        if ($search) {
            $depositsPQuery->where('name', 'like', '%' . $search . '%');
        }
        $depositsP = $depositsPQuery->paginate(5, ['*'], 'depositsP_page');

        $depositsCQuery = $team->deposits()->wherePivot('role', 'c');
        if ($search) {
            $depositsCQuery->where('name', 'like', '%' . $search . '%');
        }
        $depositsC = $depositsCQuery->paginate(5, ['*'], 'depositsC_page');

        return view('teams.deposits', compact('team', 'tags', 'depositsP', 'depositsC'));
    }

    public function store(Request $request, $team_id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'type' => 'required|integer',
            'units' => 'required|string',
            'role' => 'required|string|in:p,c',
            'tags' => 'nullable|array',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('picture')) 
        {
            $image = $request->file('picture');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/deposit_pictures', $imageName);
            $picturePath = 'deposit_pictures/' . $imageName;
        }
        else 
        {
            $picturePath = null;
        }

        $deposit = Deposit::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'units' => $validatedData['type'] == 0 ? $validatedData['units'] : 'vnt',
            'countP' => $validatedData['type'] == 0 ? 0 : null,
            'type' => $validatedData['type'],
            'picture' => $picturePath,
        ]);

        if (isset($validatedData['tags'])) {
            $deposit->tags()->attach($validatedData['tags']);
        }

        $team = Team::find($team_id);
        $team->deposits()->attach($deposit->id, ['role' => $validatedData['role']]);

        if ($validatedData['type'] == 0) {
            Unit::create([
                'deposit_id' => $deposit->id,
                'link_ext' => 0,
            ]);
        }

        return redirect()->back()->with('success', 'Pakuotė sėkmingai sukurta.');
    }

    public function store2(Request $request, $team_id)
    {
        $validatedData = $request->validate([
            'role' => 'required|string|in:p,c',
            'deposit' => 'required|exists:deposits,id',
        ]);


        $team = Team::find($team_id);
        $deposit = Deposit::find($validatedData['deposit']);

        try {
            $team->deposits()->attach($deposit->id, ['role' => $validatedData['role']]);
            if ($deposit->type == 0 && 
                !DB::table('deposit_team')
                ->where('team_id', $team_id)
                ->where('deposit_id', $deposit->id)
                ->exists()) {
                Unit::create([
                    'deposit_id' => $deposit->id,
                    'link_ext' => 0,
                ]);
            }
            return redirect()->back()->with('success', 'Pakuotė sėkmingai pridėta.');
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('error', 'Ši pakuotė jau yra pridėta su šia paskirtimi.');
            } else {
                return redirect()->back()->with('error', 'Įvyko klaida. Bandykite dar kartą.');
            }
        }
    }

    public function getUnits($depositId)
    {
        $units = Unit::where('deposit_id', $depositId)->get();
        return response()->json($units);
    }

    public function guest(Request $request)
    {
        $query = Deposit::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%'.$request->input('search').'%');
        }

        $deposits = $query->paginate(10);

        return view('deposits.guest', compact('deposits'));
    }

    public function fetchDeposits(Request $request, $team_id)
    {
        $query = $request->input('q');

        $team = Team::find($team_id);

        $invitedTeamIds = $team->invitedContracts()
            ->where('status', 'accepted')
            ->pluck('invited');

        $receivedTeamIds = $team->receivedContracts()
            ->where('status', 'accepted')
            ->pluck('inviter');

        $contractedTeamIds = $invitedTeamIds->merge($receivedTeamIds)->unique()->toArray();

        $contractedTeamIds[] = $team_id;

        $deposits = Deposit::whereHas('teams', function ($query) use ($contractedTeamIds) {
            $query->whereIn('team_id', $contractedTeamIds);
        })->where('name', 'like', '%' . $query . '%')->get();

        return response()->json($deposits);
    }

    public function destroy($deposit_id, $team_id, $role)
    {
        DB::table('deposit_team')
            ->where('team_id', $team_id)
            ->where('deposit_id', $deposit_id)
            ->where('role', $role)
            ->delete();

        return redirect()->back()->with('success', 'Pakuotė sėkmingai pašalinta.');
    }

    public function update(Request $request, $deposit_id, $team_id, $role)
    {
        $deposit = Deposit::findOrFail($deposit_id);
    
        $team = Team::findOrFail($team_id);

        if ($deposit->type == 0 && $role == 'p') 
        {
            $count = $request->input('count');
            $deposit->countP += $count;
            $deposit->save();

            $oldCount = DB::table('deposit_team')
                ->where('team_id', $team_id)
                ->where('deposit_id', $deposit_id)
                ->where('role', $role)
                ->value('count');

            DB::table('deposit_team')
                ->where('team_id', $team_id)
                ->where('deposit_id', $deposit_id)
                ->where('role', $role)
                ->update(['count' =>  $count + $oldCount]);

            return redirect()->back();
        } 
        elseif ($deposit->type == 0 && $role == 'c') 
        {
            $count = $request->input('count');
            $deposit->countC += $count;
            $deposit->save();

            $oldCount = DB::table('deposit_team')
                ->where('team_id', $team_id)
                ->where('deposit_id', $deposit_id)
                ->where('role', $role)
                ->value('count');

            DB::table('deposit_team')
                ->where('team_id', $team_id)
                ->where('deposit_id', $deposit_id)
                ->where('role', $role)
                ->update(['count' =>  $count + $oldCount]);

            return redirect()->back();
        } 
        elseif ($deposit->type == 1 && $role == 'p') 
        {
            for ($i = 0; $i < $request->input('count'); $i++)
            {
                $unit = Unit::create([
                    'state' => 'Prekyboje',
                    'deposit_id' => $deposit->id,
                ]);
                $unit->link_ext = $unit->id;
                $unit->save();
            }
            return redirect()->back();
        } 
        elseif ($deposit->type == 1 && $role == 'c') 
        {
            // Deposit type is 1 and deposit_team role is 'c'
            return redirect()->back()->with('error', 'Šio tipo pakuočių pridėti negalima.');
        }
    }
}
