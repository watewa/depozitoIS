<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\Team;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    public function index($team_id)
    {
        $team = Team::find($team_id);

        if (!Auth::user()->teams->contains($team)) {
            abort(403); 
        }

        $deposits = $team->deposits;

        return view('teams.deposits', compact('deposits'), compact('team'));
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
}
