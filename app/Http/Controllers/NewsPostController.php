<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsPost;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class NewsPostController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsPost::query();

        if ($request->has('search')) {
            $query->where('title', 'like', '%'.$request->input('search').'%');
        }

        $newsPosts = $query->latest()->paginate(10);
        return view('news.index', compact('newsPosts'));
    }

    public function show($id)
    {
        $newsPost = NewsPost::find($id);
        return view('news.show', compact('newsPost'));
    }

    protected function updateThumbnail(Request $request, $id)
    {
        $newspost = NewsPost::findOrFail($id);
    
        $request->validate([
            'newspost_picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $currentPicture = $newspost->picture;
    
        if ($currentPicture) {
            Storage::disk('public')->delete($currentPicture);
        }
    
        if ($request->hasFile('newspost_picture')) {
            $image = $request->file('newspost_picture');
            $path = $image->storeAs('newspost_pictures', Str::random(10) . '.' . $image->getClientOriginalExtension(), 'public');
    
            $newspost->update(['picture' => $path]);
        }
    
        return redirect()->route('news.index', $newspost->id)->with('status', 'newspost-picture-updated');
    }

    protected function deleteThumbnail($id)
    {
        $newspost = NewsPost::findOrFail($id);
    
        $currentPicture = $newspost->picture;
    
        if ($currentPicture) {
            Storage::disk('public')->delete($currentPicture);
        }
    
        $newspost->update(['picture' => null]);
    
        return redirect()->route('news.index', $newspost->id)->with('status', 'newspost-picture-deleted');
    }

    public function contentEdit($id)
    {
        $newsPost = NewsPost::find($id);
        return view('news.content-edit', compact('newsPost'));
    }
}
