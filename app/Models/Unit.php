<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Unit extends Model
{
    use HasFactory;

    public function setLinkExtAttribute()
    {
        Crypt::encryptString($this->id . ' ' . $this->deposit_id);
    }

    public function deposit()
    {
        return $this->belongsTo(Deposit::class);
    }
}
