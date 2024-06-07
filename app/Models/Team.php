<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Team extends Model
{
    protected $fillable = ['name', 'location', 'city', 'street', 'house_nr', 'zip_code', 'extra_line', 'picture', 'phone', 'email', 'other',];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('is_admin');
    }

    public function deposits()
    {
        return $this->belongsToMany(Deposit::class, 'deposit_team')->withPivot('role')->withTimestamps();
    }

    public function invitedContracts()
    {
        return $this->hasMany(Contract::class, 'inviter', 'id');
    }

    public function receivedContracts()
    {
        return $this->hasMany(Contract::class, 'invited', 'id');
    }

    public function contracts()
    {
        return $this->invitedContracts()->union($this->receivedContracts());
    }

    public function getTeamPicturePathAttribute()
    {
        if ($this->picture != null)
        {
           return Storage::url($this->picture);
        }
        else
        {
            return 'https://via.placeholder.com/150?text=' . strtoupper(substr($this->name, 0, 1));
        }
    }
}
