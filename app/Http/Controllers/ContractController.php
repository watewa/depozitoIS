<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\Team;

class ContractController extends Controller
{
    public function index($team_id, Request $request)
    {
        $team = Team::find($team_id);

        $contractsQuery = Contract::where('status', 'accepted')
            ->where(function ($query) use ($team_id) {
                $query->where('invited', $team_id)
                    ->orWhere('inviter', $team_id);
            })
            ->where(function ($query) use ($request) 
            {
                if ($request->has('search')) {
                    $query->whereHas('inviterTeam', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->input('search') . '%');
                    })->orWhereHas('invitedTeam', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->input('search') . '%');
                    });
                }
            });

        $invitesQuery = Contract::where('invited', $team_id)
            ->where('status', 'like', 'pending')
            ->where(function ($query) use ($request) 
            {
                if ($request->has('search')) 
                {
                    $query->whereHas('inviterTeam', function ($q) use ($request) 
                    {
                        $q->where('name', 'like', '%' . $request->input('search') . '%');
                    });
                }
            });

        $contracts = $contractsQuery->paginate(5);
        $invites = $invitesQuery->paginate(5);

        return view('teams.contracts', compact('contracts', 'invites', 'team'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'teamId' => 'required|exists:teams,id',
            'inviter' => 'required|exists:teams,id',
        ]);

        Contract::create([
            'inviter' => $validatedData['inviter'],
            'invited' => $validatedData['teamId'],
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Sutartis sėkmingai pateikta.');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $teams = Team::where('name', 'like', '%' . $search . '%')->get();

        return response()->json($teams);
    }

    public function update($id)
    {
        $contract = Contract::findOrFail($id);
        $contract->status = 'accepted';
        $contract->save();

        return redirect()->back()->with('success', 'Sutartis sėkmingai patvritinta.');
    }

    public function destroy($id)
    {
        $contract = Contract::findOrFail($id);
        $contract->delete();

        return redirect()->back()->with('success', 'Sutartis sėkmingai atmesta.');
    }
}
