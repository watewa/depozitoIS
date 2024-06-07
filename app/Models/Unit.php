<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Unit extends Model
{
    protected $fillable = ['link_ext', 'state', 'deposit_id'];

    public function setLinkExtAttribute($value)
    {
        $this->attributes['link_ext'] = '/units/'.Crypt::encryptString($value);
    }

    public function setStateAttribute($value)
    {
        $this->attributes['state'] = $value;
    }

    public function deposit()
    {
        return $this->belongsTo(Deposit::class);
    }
}
