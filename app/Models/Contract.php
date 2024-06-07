<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'inviter',
        'invited'
    ];

    public function inviterTeam()
    {
        return $this->belongsTo(Team::class, 'inviter');
    }

    public function invitedTeam()
    {
        return $this->belongsTo(Team::class, 'invited');
    }
}
