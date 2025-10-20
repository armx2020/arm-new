<?php

namespace App\Http\Controllers\InformUs;

use App\Entity\Actions\PlaceAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Group\StoreGroupRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class PlaceController extends Controller
{

    public function __construct(private PlaceAction $placeAction)
    {
        $this->placeAction = $placeAction;
    }

    public function index(Request $request)
    {
        $categories = Category::query()->places()->active()->where('category_id', null)->with('categories')->orderBy('sort_id')->get();

        return view('inform-us.create-place', [
            'categories' => $categories,
        ]);
    }

    public function store(StoreGroupRequest $request)
    {
        $place = $this->placeAction->store($request, null, false);

        return redirect()->route('inform-us.place')->with('success', "Спасибо, что делитесь полезной информацией! Благодаря вам наше сообщество становится более полезным и дружным. Мы рады, что вы с нами!");
    }
}
