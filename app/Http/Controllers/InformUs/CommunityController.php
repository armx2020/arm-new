<?php

namespace App\Http\Controllers\InformUs;

use App\Entity\Actions\CommunityAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Group\StoreGroupRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CommunityController extends Controller
{

    public function __construct(private CommunityAction $communityAction)
    {
        $this->communityAction = $communityAction;
    }

    public function index(Request $request)
    {
        $categories = Category::query()->communities()->active()->where('category_id', null)->with('categories')->orderBy('sort_id')->get();

        return view('inform-us.create-community', [
            'categories' => $categories,
        ]);
    }

    public function store(StoreGroupRequest $request)
    {
        $community = $this->communityAction->store($request, null, false);

        return redirect()->route('inform-us.community')->with('success', "Спасибо, что делитесь полезной информацией! Благодаря вам наше сообщество становится более полезным и дружным. Мы рады, что вы с нами!");
    }
}
