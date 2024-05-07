<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use Illuminate\Support\Facades\Crypt;

class UnitController extends Controller
{
    public function show($hash)
    {
        $decodedText = Crypt::decryptString($hash);
        $id = explode(' ', $decodedText)[0];
        $unit = Unit::with('deposit')->findOrFail($id);

        return view('units.show', compact('unit'));
    }
}
