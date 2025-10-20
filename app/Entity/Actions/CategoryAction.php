<?php

namespace App\Entity\Actions;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Image;

class CategoryAction
{
    public function store($request): Category
    {
        $category = new Category();

        $category->name = $request->name;
        $category->sort_id = $request->sort_id;
        $category->entity_type_id = $request->entity_type_id;
        $category->category_id = $request->parent;

        if ($request->image) {
            $category->image = $request->file('image')->store('uploaded', 'public');
            Image::make('storage/' . $category->image)->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save();
        }

        $category->save();

        return $category;
    }

    public function update($request, $category): Category
    {
        $category->name = $request->name;
        $category->sort_id = $request->sort_id;
        $category->entity_type_id = $request->entity_type_id;
        $category->category_id = $request->parent;

        if ($request->image_remove == 'delete') {
            Storage::delete('public/' . $category->image);
            $category->image = null;
        }

        if ($request->image) {
            Storage::delete('public/' . $category->image);
            $category->image = $request->file('image')->store('groups', 'public');
            Image::make('storage/' . $category->image)->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save();
        }

        $category->update();

        return $category;
    }
}
