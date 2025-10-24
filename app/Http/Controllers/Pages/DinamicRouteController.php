<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Http\Traits\RegionTrait;
use App\Models\Category;
use App\Models\Entity;
use App\Models\EntityType;
use Doctrine\Inflector\InflectorFactory;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DinamicRouteController extends Controller
{
    use RegionTrait;

    protected $inflector;
    protected $request;
    protected $quantityOfDisplayed = 20; // Количество отоброжаемых сущностей

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->inflector = InflectorFactory::create()->build();
    }

    public function __call($method, $parameters)
    {
        $methodArray = explode('-', $method);

        $category = $parameters[1] ?? null;
        $subCategory = $parameters[2] ?? null;

        if (isset($methodArray[1]) && $methodArray[1] == 'region') {

            $regionTranslit = $parameters[0] ?? null;
            $category = $parameters[1] ?? null;
            $subCategory = $parameters[2] ?? null;

            return $this->region($methodArray[0], $regionTranslit, $category, $subCategory,);
        } elseif (isset($methodArray[1]) && $methodArray[1] == 'show') {
            $idOrTranscript = $parameters[0] ?? null;

            return $this->show($methodArray[0], $idOrTranscript);
        } else {

            $category = $parameters[0] ?? null;
            $subCategory = $parameters[1] ?? null;

            return $this->index($methodArray[0], $category, $subCategory);
        }
    }

    public function index($plural, $category = null, $subCategory = null)
    {
        $region = $this->getRegion($this->request, null);

        $type = EntityType::where('transcription', $plural)->First();

        if (!$type) {
            return redirect()->route('home');
        }

        $entities = Entity::query()->active()->where('entity_type_id', $type->id)->with('fields', 'offers', 'images', 'city', 'region')->withCount('offers');
        $categories = Category::query()->main()->active()->where('entity_type_id', $type->id)->get();
        $subCategories = null;

        if ($category) {
            $category = Category::active()->main()->where('entity_type_id', $type->id)->select('id', 'transcription')->where('transcription', $category)->First();

            if ($category) {
                $category_id = $category->id;
                $subCategories = Category::where('category_id', $category_id)->get();
            } else {
                return redirect()->route("$type->transcription.index", ['regionTranslit' => $region->transcription]);
            }

            if ($subCategory) {
                $subCategory = Category::active()->where('entity_type_id', $type->id)->select('id', 'transcription')->where('transcription', $subCategory)->First();

                if ($subCategory) {
                    $subCategory_id = $subCategory->id;
                } else {
                    return redirect()->route("$type->transcription.index", ['regionTranslit' => $region->transcription]);
                }

                $entities = $entities
                    ->where(function (Builder $query) use ($category_id, $subCategory_id) {
                        $query
                            ->where('category_id', $category_id) // ID категории
                            ->whereHas('fields', function ($que) use ($subCategory_id) {
                                $que->where('category_entity.category_id', '=', $subCategory_id); // ID подкатегории
                            });
                    });
            } else {
                $entities = $entities
                    ->where(function (Builder $query) use ($category_id) {
                        $query
                            ->where('category_id', $category_id) // ID категории
                            ->orWhereHas('fields', function ($que) use ($category_id) {
                                $que->where('category_entity.main_category_id', '=', $category_id); // ID категории
                            });
                    });
            }
        }

        $entities = $entities->orderByDesc('sort_id')->paginate($this->quantityOfDisplayed);

        $entitySingular = $this->inflector->singularize($type->transcription);

        $entityName = "$type->name";
        $entityTranscription = "$type->transcription";
        $entityShowRoute = "$entitySingular.show";

        return view('pages.entity.index', [
            'categoryUri' => null,
            'entityName' => $entityName,
            'entityTranscription' => $entityTranscription,
            'entityShowRoute' => $entityShowRoute,
            'entities' => $entities,
            'selectedCategory' => $category,
            'categories' => $categories,
            'selectedSubCategory' => $subCategory,
            'subCategories' => $subCategories
        ]);
    }

    public function region($plural, $regionTranslit = null, $category = null, $subCategory = null)
    {
        $region = $this->getRegion($this->request, $regionTranslit);

        if (!$region) {
            return redirect()->route('home');
        }

        $type = EntityType::where('transcription', $plural)->First();

        if (!$type) {
            return redirect()->route('home', ['regionTranslit' => $region->transcription]);
        }

        $entities = Entity::query()->active()->where('entity_type_id', $type->id)->with('fields', 'offers', 'images', 'city', 'region')->withCount('offers');
        $categories = Category::query()->main()->active()->where('entity_type_id', $type->id)->get();
        $subCategories = null;

        if ($regionTranslit) {
            $entities = $entities->orderByRaw("CASE WHEN region_id = ? THEN 0 ELSE 1 END", [$region->id])->orderBy('offers_count', 'desc');
        }

        if ($category) {
            $category = Category::active()->main()->where('entity_type_id', $type->id)->select('id', 'transcription')->where('transcription', $category)->First();

            if ($category) {
                $category_id = $category->id;
                $subCategories = Category::where('category_id', $category_id)->get();
            } else {
                return redirect()->route("$type->transcription.region", ['regionTranslit' => $region->transcription]);
            }

            if ($subCategory) {
                $subCategory = Category::active()->where('entity_type_id', $type->id)->select('id', 'transcription')->where('transcription', $subCategory)->First();

                if ($subCategory) {
                    $subCategory_id = $subCategory->id;
                } else {
                    return redirect()->route("$type->transcription.region", ['regionTranslit' => $region->transcription]);
                }

                $entities = $entities
                    ->where(function (Builder $query) use ($category_id, $subCategory_id) {
                        $query
                            ->where('category_id', $category_id) // ID категории
                            ->whereHas('fields', function ($que) use ($subCategory_id) {
                                $que->where('category_entity.category_id', '=', $subCategory_id); // ID подкатегории
                            });
                    });
            } else {
                $entities = $entities
                    ->where(function (Builder $query) use ($category_id) {
                        $query
                            ->where('category_id', $category_id) // ID категории
                            ->orWhereHas('fields', function ($que) use ($category_id) {
                                $que->where('category_entity.main_category_id', '=', $category_id); // ID категории
                            });
                    });
            }
        }

        $entities = $entities->orderByDesc('sort_id')->paginate($this->quantityOfDisplayed);

        $entitySingular = $this->inflector->singularize($type->transcription);

        $entityName = "$type->name";
        $entityTranscription = "$type->transcription";
        $entityShowRoute = "$entitySingular.show";

        return view('pages.entity.index', [
            'categoryUri' => null,
            'entityName' => $entityName,
            'entityTranscription' => $entityTranscription,
            'entityShowRoute' => $entityShowRoute,
            'entities' => $entities,
            'selectedCategory' => $category,
            'categories' => $categories,
            'selectedSubCategory' => $subCategory,
            'subCategories' => $subCategories
        ]);
    }

    public function show($plural, $idOrTranscript)
    {
        $type = EntityType::where('transcription', $plural)->First();

        if (!$type) {
            return redirect()->route('home', ['regionTranslit' => $this->request->session()->get('regionTranslit') ?: null]);
        }

        $entitySingular = $this->inflector->singularize($type->transcription);

        $entityName = "$type->name";
        $entityTranscription = "$type->transcription";
        $entityShowRoute = "$entitySingular.show";

        $entity = Entity::query()->active();

        if (is_numeric($idOrTranscript)) {
            $entity = $entity->where('id', $idOrTranscript)->First();
        } else {
            $entity = $entity->where('transcription', $idOrTranscript)->First();
        }

        if (!$entity) {
            return redirect()->route('home', ['regionTranslit' => $this->request->session()->get('regionTranslit') ?: null]);
        }

        $otherEntities = $entity->getSimilarEntities();

        return view('pages.entity.show', [
            'categoryUri' => null,
            'entityName' => $entityName,
            'entityTranscription' => $entityTranscription,
            'entity' => $entity,
            'otherEntities' => $otherEntities,
            'entityShowRoute' => $entityShowRoute
        ]);
    }
}
