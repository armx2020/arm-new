<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use Doctrine\Inflector\InflectorFactory;
use Illuminate\Http\Request;

class EntityForHomePageController extends Controller
{
    public function get(Request $request)
    {
        $input = $request->all();
        $morePages = false;

        $inflector = InflectorFactory::create()->build();

        if (!empty($input['query'])) {
            $data = Entity::select('id', 'name', 'entity_type_id')->active()->where("name", "LIKE", "%{$input['query']}%")->orWhere("id", "=", "{$input['query']}")->orWhere("description", "LIKE", "%{$input['query']}%")->limit(10)->get();
        } else {
            $data = Entity::select('id', 'name', 'entity_type_id')->active()->simplePaginate(10);

            $pagination_obj = json_encode($data);

            if ($data->nextPageUrl() !== null) {
                $morePages = true;
            }
        }

        $entities['results'] = [];
        
        if (count($data) > 0) {
            foreach ($data as $entity) {

                $singular = $inflector->singularize($entity->type->transcription);

                
                $entities['results'][] = array(
                    "id" => $entity->id,
                    "text" => $entity->name,
                    'url' => route($singular . '.show', ['idOrTranscript' => $entity->id])
                );
            }
        }

        $entities['pagination'] = array(
            "more" => $morePages
        );

        return response()->json($entities);
    }
}
