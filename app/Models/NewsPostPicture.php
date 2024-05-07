<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsPostPicture extends Model
{
    protected $fillable = ['news_post_id', 'picture'];

    public function newsPost()
    {
        return $this->belongsTo(NewsPost::class);
    }
}
