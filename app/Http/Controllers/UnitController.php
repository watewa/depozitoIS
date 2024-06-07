<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    public function show($hash)
    {
        $id = Crypt::decryptString($hash);
        $unit = Unit::with('deposit')->findOrFail($id);

        return view('units.show', compact('unit'));
    }

    public function update($id)
    {
        $unit = Unit::find($id);
        if($unit->state == 'Prekyboje')
        {
            $unit->state = 'Parduotas';
        }
        else if($unit->state == 'Parduotas')
        {
            $unit->state = 'Grąžintas';
        }
        else if($unit->state == 'Grąžintas')
        {
            $unit->state = 'Prekyboje';
        }
        $unit->save();
        return redirect()->back();
    }
    

    public function encryptAll()
    {
        $units = Unit::all();

        foreach ($units as $unit) {
            $encryptedLinkExt = Crypt::encryptString($unit->id . ' ' . $unit->deposit_id);
            
            DB::table('units')
            ->where('id', $unit->id)
            ->update(['link_ext' => $encryptedLinkExt]);
        }

        return 'All units encrypted successfully.';
    }

    public function c()
    {
        return view('crypt');
    }
}
