<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Deposit extends Model
{
    public function team()
    {
        return $this->belongsTo(Team::class);
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
        if ($this->thumbnail != null)
        {
           return Storage::url($this->thumbnail);
        }
        else
        {
            return 'https://via.placeholder.com/150?text=' . strtoupper(substr($this->name, 0, 1));
        }
    }
}