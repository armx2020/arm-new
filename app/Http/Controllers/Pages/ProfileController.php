<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Intervention\Image\Facades\Image as Image;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = User::find($id);

        if (empty($user)) {

            if ($request->session()->get('region') && $request->session()->get('region') !== 'russia') {
                return redirect()->route('home', ['regionTranslit' => $request->session()->get('region')])->with('alert', 'Пользователь не найден');
            } else {
                return redirect()->route('home')->with('alert', 'Пользователь не найден');
            }
        }

        $sum =  ($user->image ? 10 : 0) +
            ($user->phone ? 10 : 0) +
            ($user->viber ? 5 : 0) +
            ($user->whatsapp ? 5 : 0) +
            ($user->telegram ? 5 : 0) +
            ($user->instagram ? 5 : 0) +
            ($user->vkontakte ? 5 : 0);

        $fullness = (round(($sum / 45) * 100));

        return view('pages.user.index', [
            'user' => $user,
            'fullness' => $fullness,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'firstname' => ['required', 'string', 'max:32'],
            'viber' => ['max:36'],
            'whatsapp' => ['max:36'],
            'telegram' => ['max:36'],
            'instagram' => ['max:36'],
            'vkontakte' => ['max:36'],
            'image' => ['image']
        ]);

        $user = User::findOrFail(Auth::user()->id);
        $city = City::with('region')->find($request->project_city);

        if (empty($city)) {
            $city = City::find(1);
        }

        if ($request->image_remove == 'delete') {
            Storage::delete('public/' . $user->image);
            $user->image = null;
        }
        if ($request->image) {
            Storage::delete('public/' . $user->image);
            $user->image = $request->file('image')->store('users', 'public');
            Image::make('storage/' . $user->image)->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save();
        }

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->city_id = $request->user_city;
        $user->region_id = $city->region->id;
        $user->viber = $request->viber;
        $user->whatsapp = $request->whatsapp;
        $user->telegram = $request->telegram;
        $user->instagram = $request->instagram;
        $user->vkontakte = $request->vkontakte;

        $user->update();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

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
}
