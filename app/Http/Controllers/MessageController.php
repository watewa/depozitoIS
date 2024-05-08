<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;


class MessageController extends Controller
{
    public function index($team_id)
    {
        $team = Team::find($team_id);
        $messages = Message::where('team_id', $team_id)->oldest()->get();

        return view('teams.messages', compact('messages'), compact('team'));
    }

    public function dynamic($team_id)
    {
        $messages = Message::where('team_id', $team_id)->oldest()->get();

        foreach ($messages as $message) {
            $user = User::find($message->user_id);
            $message->profile_picture_path = $user->getProfilePicturePathAttribute();
        }

        return response()->json(['messages' => $messages]);
    }
        
    public function store(Request $request, $team_id)
    {
        $request->validate([
            'content' => 'required',
        ]);
    
        $data = [
            'user_id' => Auth::user()->id,
            'team_id' => $team_id,
            'content' => $request->content,
        ];

        Message::create($data);
    
        return back();
    }
}
