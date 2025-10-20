<?php

namespace App\Entity\Actions;

use App\Models\Appeal;
use Intervention\Image\Facades\Image as Image;
use Illuminate\Support\Facades\Storage;

class AppealAction
{
    public function store($request, $entityId = null, $userId = null): Appeal
    {
        $appeal = new Appeal();

        $appeal->name = $request->name ?: 'не указано';
        $appeal->phone = $request->phone?: 'не указано';
        $appeal->message = $request->message;
        $appeal->entity_id = $entityId;
        $appeal->user_id = $userId;

        $appeal->save();

        if ($request->image_1) {
            $appeal->images()->create([
                'path' => $request->file('image_1')->store('uploaded', 'public')
            ]);
            Image::make('storage/' . $appeal->images()->get()[0]->path)->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save();
        }

        if ($request->image_2) {
            $appeal->images()->create([
                'path' => $request->file('image_2')->store('uploaded', 'public')
            ]);
            Image::make('storage/' . $appeal->images()->get()[1]->path)->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save();
        }

        if ($request->image_3) {
            $appeal->images()->create([
                'path' => $request->file('image_3')->store('uploaded', 'public')
            ]);
            Image::make('storage/' . $appeal->images()->get()[2]->path)->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save();
        }

        if ($request->image_4) {
            $appeal->images()->create([
                'path' => $request->file('image_4')->store('uploaded', 'public')
            ]);
            Image::make('storage/' . $appeal->images()->get()[3]->path)->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save();
        }

        if ($request->image_5) {
            $appeal->images()->create([
                'path' => $request->file('image_5')->store('uploaded', 'public')
            ]);
            Image::make('storage/' . $appeal->images()->get()[4]->path)->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save();
        }

        return $appeal;
    }

    public function update($request, $appeal): Appeal
    {
        $appeal->message = $request->message;
        $appeal->activity = $request->activity ? 1 : 0;

        if ($request->image_remove_1 == 'delete' || $request->image_1) {
            if (isset($appeal->images()->get()[0])) {
                Storage::delete('public/' . $appeal->images()->get()[0]->path);
                $appeal->images()->get()[0]->delete();
            }
        }

        if ($request->image_remove_2 == 'delete'  || $request->image_2) {
            if (isset($appeal->images()->get()[1])) {
                Storage::delete('public/' . $appeal->images()->get()[1]->path);
                $appeal->images()->get()[1]->delete();
            }
        }

        if ($request->image_remove_3 == 'delete'  || $request->image_3) {
            if (isset($appeal->images()->get()[2])) {
                Storage::delete('public/' . $appeal->images()->get()[2]->path);
                $appeal->images()->get()[2]->delete();
            }
        }

        if ($request->image_remove_4 == 'delete' || $request->image_4) {
            if (isset($appeal->images()->get()[3])) {
                Storage::delete('public/' . $appeal->images()->get()[3]->path);
                $appeal->images()->get()[3]->delete();
            }
        }

        if ($request->image_remove_5 == 'delete' || $request->image_5) {
            if (isset($appeal->images()->get()[4])) {
                Storage::delete('public/' . $appeal->images()->get()[4]->path);
                $appeal->images()->get()[4]->delete();
            }
        }

        // images
        if ($request->image_1) {
            $appeal->images()->create([
                'path' => $request->file('image_1')->store('uploaded', 'public')
            ]);
            Image::make('storage/' . $appeal->images()->get()[0]->path)->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save();
        }

        if ($request->image_2) {
            $appeal->images()->create([
                'path' => $request->file('image_2')->store('uploaded', 'public')
            ]);
            Image::make('storage/' . $appeal->images()->get()[1]->path)->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save();
        }

        if ($request->image_3) {
            $appeal->images()->create([
                'path' => $request->file('image_3')->store('uploaded', 'public')
            ]);
            Image::make('storage/' . $appeal->images()->get()[2]->path)->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save();
        }

        if ($request->image_4) {
            $appeal->images()->create([
                'path' => $request->file('image_4')->store('uploaded', 'public')
            ]);
            Image::make('storage/' . $appeal->images()->get()[3]->path)->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save();
        }

        if ($request->image_5) {
            $appeal->images()->create([
                'path' => $request->file('image_5')->store('uploaded', 'public')
            ]);
            Image::make('storage/' . $appeal->images()->get()[4]->path)->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save();
        }

        $appeal->update();

        return $appeal;
    }

    public function storePhotoToEntity($request, $entity)
    {
        if ($request->image_1) {
            $images_1 = $entity->images()->create([
                'path' => $request->file('image_1')->store('uploaded', 'public'),
                'checked' => false,
            ]);
            Image::make('storage/' . $images_1->path)->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save();
        }

        if ($request->image_2) {
            $image_2 = $entity->images()->create([
                'path' => $request->file('image_2')->store('uploaded', 'public'),
                'checked' => false,
            ]);
            Image::make('storage/' . $image_2->path)->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save();
        }

        if ($request->image_3) {
            $image_3 = $entity->images()->create([
                'path' => $request->file('image_3')->store('uploaded', 'public'),
                'checked' => false,
            ]);
            Image::make('storage/' . $image_3->path)->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save();
        }

        if ($request->image_4) {
            $images_4 = $entity->images()->create([
                'path' => $request->file('image_4')->store('uploaded', 'public'),
                'checked' => false,
            ]);
            Image::make('storage/' . $images_4->path)->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save();
        }

        if ($request->image_5) {
            $images_5 = $entity->images()->create([
                'path' => $request->file('image_5')->store('uploaded', 'public'),
                'checked' => false,
            ]);
            Image::make('storage/' . $images_5->path)->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save();
        }

        return $entity;
    }
}
