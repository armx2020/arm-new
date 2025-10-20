<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appeal;
use App\Models\Entity;
use App\Models\Image;
use App\Models\Scopes\CheckedScope;
use App\Models\Scopes\SortAscScope;
use App\Models\User;


class DashboardController extends Controller
{
    public function index()
    {
        $countUsersAll = User::count();
        $countUsersToday = User::whereDate('created_at', '=', date("Y-m-d"))->count();

        $countCompaniesAll = Entity::companies()->count();
        $countCompaniesToday = Entity::companies()->whereDate('created_at', '=', date("Y-m-d"))->count();

        $countGroupsAll = Entity::groups()->count();
        $countGroupsToday = Entity::groups()->whereDate('created_at', '=', date("Y-m-d"))->count();

        $users = User::orderBy('id', 'desc')->limit(5)->get();
        $appeals = Appeal::active()->orderBy('id', 'desc')->limit(5)->get();
        $images = Image::query()->withOutGlobalScopes([SortAscScope::class, CheckedScope::class])->limit(10)->with('imageable')->where('checked', false)->orderByDesc('id')->get();

        return view('admin.dashboard', [
            'countUsersAll' => $countUsersAll,
            'countUsersToday' => $countUsersToday,
            'countCompaniesAll' => $countCompaniesAll,
            'countCompaniesToday' => $countCompaniesToday,
            'countGroupsAll' => $countGroupsAll,
            'countGroupsToday' => $countGroupsToday,
            'users' => $users,
            'appeals' => $appeals,
            'images' => $images,
        ]);
    }
}
