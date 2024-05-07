<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    public function deposit()
    {
        return $this->belongsToMany(Deposit::class);
    }

    public function textColor()
    {
        $bgColor = $this->color;
        list($r, $g, $b) = sscanf($bgColor, "#%02x%02x%02x");
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
        $textColor = $luminance > 0.75 ? '#000000' : '#ffffff';
        return $textColor;
    }

}
