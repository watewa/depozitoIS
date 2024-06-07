<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class NewsPost extends Model
{
    protected $fillable = ['title', 'content', 'author_id', 'thumbnail', 'pictures'];

    protected $casts = [
        'pictures' => 'array'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getThumbnailPathAttribute()
    {
        if ($this->thumbnail != null)
        {
           return Storage::url($this->thumbnail);
        }
        else
        {
            return 'https://via.placeholder.com/150?text=' . strtoupper(substr($this->title, 0, 1));
        }
    }
}
