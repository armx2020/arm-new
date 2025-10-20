<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Http\Traits\RegionTrait;
use App\Models\Entity;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use RegionTrait;

    public function home(Request $request, $regionTranslit = null)
    {
        $region = $this->getRegion($request, $regionTranslit);

        if (!$region) {
            return redirect()->route('home');
        }

        $group = Entity::query()->groups();

        if ($region && $region->id == 1) {
            $group = $group->where('region_id', 1)->first();
        } else {
            $group = $group->where('region_id', $region->id)->first();
        }

        if (empty($group)) {
            $group = Entity::where('region_id', 1)->first();
        }

        return view('home', [
            'group' => $group,
        ]);
    }

    public function privacyPolicy(Request $request)
    {
        return view('pages.privacy-policy');
    }

    public function conditionOfUse(Request $request)
    {
        return view('pages.condition-of-use');
    }
}
