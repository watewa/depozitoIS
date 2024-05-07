<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Team extends Model
{
    protected $fillable = ['name'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('is_admin');
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function getTeamPicturePathAttribute()
    {
        if ($this->profile_picture != null)
        {
           return Storage::url($this->profile_picture);
        }
        else
        {
            return 'https://via.placeholder.com/150?text=' . strtoupper(substr($this->name, 0, 1));
        }
    }
}
