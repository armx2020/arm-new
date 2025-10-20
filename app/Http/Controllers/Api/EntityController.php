<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use Illuminate\Http\Request;

class EntityController extends Controller
{
    public function get(Request $request)
    {
        $input = $request->all();
        $morePages = false;

        if (!empty($input['query'])) {
            $data = Entity::where("name", "LIKE", "%{$input['query']}%")->get();
        } else {
            $data = Entity::orderBy('name')->simplePaginate(10);

            $pagination_obj = json_encode($data);

            if ($data->nextPageUrl() !== null) {
                $morePages = true;
            }
        }

        $entities['results'] = [];
        if (count($data) > 0) {
            foreach ($data as $entity) {
                $entities['results'][] = array(
                    "id" => $entity->id,
                    "text" => $entity->name . ' (' . $entity->type->name . ')',
                );
            }
        }

        $entities['pagination'] = array(
            "more" => $morePages
        );

        return response()->json($entities);
    }
}
