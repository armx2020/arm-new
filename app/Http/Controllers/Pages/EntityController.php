<?php

namespace App\Http\Controllers\Pages;

use App\Entity\Actions\AppealAction;
use App\Http\Controllers\Controller;
use App\Models\Entity;
use Doctrine\Inflector\InflectorFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image as Image;

class EntityController extends Controller
{
    protected $inflector;

    public function __construct(private AppealAction $appealAction)
    {
        $this->appealAction = $appealAction;
        $this->inflector = InflectorFactory::create()->build();
    }

    public function edit(Request $request, $idOrTranscript)
    {
        $entity = Entity::query()->active()->with('type');

        if (is_numeric($idOrTranscript)) {
            $entity = $entity->where('id', $idOrTranscript)->First();
        } else {
            $entity = $entity->where('transcription', $idOrTranscript)->First();
        }

        if (!$entity) {
            return redirect()->route('home');
        }

        $type = $entity->type()->First();

        $entitySingular = $this->inflector->singularize($type->transcription);

        $entityName = "$type->name";
        $entityTranscription = "$type->transcription";
        $entityShowRoute = "$entitySingular.show";

        return view('pages.entity.edit', [
            'categoryUri' => null,
            'entityName' => $entityName,
            'entityTranscription' => $entityTranscription,
            'entityShowRoute' => $entityShowRoute,
            'entity' => $entity,
        ]);
    }

    public function update(Request $request, $idOrTranscript)
    {
        $entity = Entity::query()->active();

        if (is_numeric($idOrTranscript)) {
            $entity = $entity->where('id', $idOrTranscript)->First();
        } else {
            $entity = $entity->where('transcription', $idOrTranscript)->First();
        }

        if (!$entity) {
            return redirect()->route('home');
        }

        $appeal = $this->appealAction->store($request, $entity->id, Auth::user()?->id);

        if (!$appeal) {
            switch ($entity->entity_type_id) {
                case 4:
                    return redirect()->route('community.show', ['idOrTranscript' => $entity->id])->with('warning', 'Не удалось, попробуйте позднее');
                    break;
                case 3:
                    return redirect()->route('place.show', ['idOrTranscript' => $entity->id])->with('warning', 'Не удалось, попробуйте позднее');
                    break;
                case 2:
                    return redirect()->route('group.show', ['idOrTranscript' => $entity->id])->with('warning', 'Не удалось, попробуйте позднее');
                    break;
                default:
                    return redirect()->route('company.show', ['idOrTranscript' => $entity->id])->with('warning', 'Не удалось, попробуйте позднее');
                    break;
            }
        }

        switch ($entity->entity_type_id) {
            case 4:
                return redirect()->route('community.show', ['idOrTranscript' => $entity->id])->with('success', "Спасибо за ваш вклад в наше сообщество! Ваша информация поможет многим найти надежные компании и услуги. Мы ценим вашу активность и заботу о наших земляках!");
                break;
            case 3:
                return redirect()->route('place.show', ['idOrTranscript' => $entity->id])->with('success', "Спасибо за ваш вклад в наше сообщество! Ваша информация поможет многим найти надежные компании и услуги. Мы ценим вашу активность и заботу о наших земляках!");
                break;
            case 2:
                return redirect()->route('group.show', ['idOrTranscript' => $entity->id])->with('success', "Спасибо за ваш вклад в наше сообщество! Ваша информация поможет многим найти надежные компании и услуги. Мы ценим вашу активность и заботу о наших земляках!");
                break;
            default:
                return redirect()->route('company.show', ['idOrTranscript' => $entity->id])->with('success', "Спасибо за ваш вклад в наше сообщество! Ваша информация поможет многим найти надежные компании и услуги. Мы ценим вашу активность и заботу о наших земляках!");
                break;
        }
    }

    public function editPhoto(Request $request, $idOrTranscript)
    {
        $entity = Entity::query()->active()->with('type');

        if (is_numeric($idOrTranscript)) {
            $entity = $entity->where('id', $idOrTranscript)->First();
        } else {
            $entity = $entity->where('transcription', $idOrTranscript)->First();
        }

        if (!$entity) {
            return redirect()->route('home');
        }

        $type = $entity->type()->First();

        $entitySingular = $this->inflector->singularize($type->transcription);

        $entityName = "$type->name";
        $entityTranscription = "$type->transcription";
        $entityShowRoute = "$entitySingular.show";

        return view('pages.entity.photo', [
            'categoryUri' => null,
            'entityName' => $entityName,
            'entityTranscription' => $entityTranscription,
            'entityShowRoute' => $entityShowRoute,
            'entity' => $entity,
        ]);
    }

    public function updatePhoto(Request $request, $idOrTranscript)
    {
        $entity = Entity::query()->active();

        if (is_numeric($idOrTranscript)) {
            $entity = $entity->where('id', $idOrTranscript)->First();
            if ($entity != null) {
                if ($request->hasFile('images')) {
                    $images = $entity->images(false);
                    $lastImage = $images->orderBy('sort_id', 'DESC')->first()->sort_id;
                    $imagesCount = $images->count();
                    if ((count($request->images) + $imagesCount) > 20) {
                        return redirect()->back()->with('error', 'Количество изображений превысило лимит');
                    }
                    foreach ($request->file('images') as $sortId => $file) {
                        $sortId += ($lastImage + 1);
                        $path = $file->store('uploaded', 'public');

                        $imageEntity = $entity->images()->create([
                            'path' => $path,
                            'sort_id' => $sortId,
                            'checked' => 0,
                        ]);

                        Image::make('storage/' . $imageEntity->path)
                            ->resize(400, null, function ($constraint) {
                                $constraint->aspectRatio();
                            })
                            ->save();
                    }
                }
            }
        } else {
            $entity = $entity->where('transcription', $idOrTranscript)->First();
        }

        if (!$entity) {
            return redirect()->route('home');
        }

        $entity = $this->appealAction->storePhotoToEntity($request, $entity);

        switch ($entity->entity_type_id) {
            case 4:
                return redirect()->route('community.show', ['idOrTranscript' => $entity->id])->with('success', "Спасибо за ваш вклад в наше сообщество! Ваша информация поможет многим найти надежные компании и услуги. Мы ценим вашу активность и заботу о наших земляках!");
                break;
            case 3:
                return redirect()->route('place.show', ['idOrTranscript' => $entity->id])->with('success', "Спасибо за ваш вклад в наше сообщество! Ваша информация поможет многим найти надежные компании и услуги. Мы ценим вашу активность и заботу о наших земляках!");
                break;
            case 2:
                return redirect()->route('group.show', ['idOrTranscript' => $entity->id])->with('success', "Спасибо за ваш вклад в наше сообщество! Ваша информация поможет многим найти надежные компании и услуги. Мы ценим вашу активность и заботу о наших земляках!");
                break;
            default:
                return redirect()->route('company.show', ['idOrTranscript' => $entity->id])->with('success', "Спасибо за ваш вклад в наше сообщество! Ваша информация поможет многим найти надежные компании и услуги. Мы ценим вашу активность и заботу о наших земляках!");
                break;
        }
    }
}
