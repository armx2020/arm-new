<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function get(Request $request)
    {
        $input = $request->all();
        $morePages = false;

        if (!empty($input['query'])) {
            $data = City::where("name", "LIKE", "%{$input['query']}%")->get();
        } else {
            $data = City::orderBy('name')->simplePaginate(10);

            $pagination_obj = json_encode($data);

            if ($data->nextPageUrl() !== null) {
                $morePages = true;
            }
        }

        $cities['results'] = [];
        if (count($data) > 0) {
            foreach ($data as $city) {
                $cities['results'][] = array(
                    "id" => $city->id,
                    "text" => $city->name,
                );
            }
        }

        $cities['pagination'] = array(
            "more" => $morePages
        );

        return response()->json($cities);
    }
}
