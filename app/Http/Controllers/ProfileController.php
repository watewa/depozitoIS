<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    protected function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $currentProfilePicture = Auth::user()->profile_picture;

        if ($currentProfilePicture) {
            Storage::disk('public')->delete($currentProfilePicture);
        }

        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $path = $image->storeAs('profile_pictures', Str::random(10) . '.' . $image->getClientOriginalExtension(), 'public');

            Auth::user()->update(['profile_picture' => $path]);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-picture-updated');
    }

    protected function deleteProfilePicture(Request $request)
    {
        $currentProfilePicture = Auth::user()->profile_picture;

        if ($currentProfilePicture) {
            Storage::disk('public')->delete($currentProfilePicture);
        }

        Auth::user()->update(['profile_picture' => null]);

        return Redirect::route('profile.edit')->with('status', 'profile-picture-deleted');
    }
}
