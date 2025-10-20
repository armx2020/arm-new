<?php

namespace App\Http\Controllers\InformUs;

use App\Entity\Actions\GroupAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Group\StoreGroupRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class GroupController extends Controller
{

    public function __construct(private GroupAction $groupAction)
    {
        $this->groupAction = $groupAction;
    }

    public function index(Request $request)
    {
        $categories = Category::query()->groups()->active()->where('category_id', null)->with('categories')->orderBy('sort_id')->get();

        return view('inform-us.create-group', [
            'categories' => $categories,
        ]);
    }

    public function store(StoreGroupRequest $request)
    {
        $group = $this->groupAction->store($request, null, false);

        return redirect()->route('inform-us.group')->with('success', "Спасибо, что делитесь полезной информацией! Благодаря вам наше сообщество становится более полезным и дружным. Мы рады, что вы с нами!");
    }
}
