<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Http\Traits\RegionTrait;
use App\Models\Entity;
use App\Services\DemoDataService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use RegionTrait;

    public function home(Request $request, DemoDataService $demo, $regionTranslit = null)
    {
        // DEMO режим: быстрая загрузка без MySQL ⚡
        if ($demo->isDemoMode()) {
            $entities = $demo->getEntities();
            $group = $entities->first();
            
            return view('home', [
                'group' => (object)[
                    'id' => $group['id'] ?? 1,
                    'name' => $group['name'] ?? 'Ресторан "Арарат"',
                    'entity_type_id' => $group['entity_type_id'] ?? 1,
                    'region_id' => $group['region_id'] ?? 1,
                ]
            ]);
        }

        // БОЕВОЙ режим: реальные данные из MySQL 🔴
        $region = $this->getRegion($request, $regionTranslit);

        if (!$region) {
            return redirect()->route('home');
        }

        $group = Entity::query()
            ->where('entity_type_id', 2) // Groups
            ->where('region_id', $region->id)
            ->first();

        if (empty($group)) {
            $group = Entity::where('entity_type_id', 2)->first();
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
