<?php

namespace App\Entity\Actions;

use App\Models\EntityType;


class TypeAction
{
    public function store($request): EntityType
    {
        $type = EntityType::create(['name' => $request->name]);
        
        return $type;
    }

    public function update($request, $type): EntityType
    {
        $type->name = $request->name;
        $type->update();

        return $type;
    }
}
