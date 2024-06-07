<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Deposit extends Model
{
    protected $fillable = ['name', 'description', 'countP', 'countC', 'type', 'units', 'picture'];

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'deposit_team')->withPivot('role')->withTimestamps();
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getThumbnailPathAttribute()
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