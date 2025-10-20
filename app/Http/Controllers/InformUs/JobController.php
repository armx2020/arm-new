<?php

namespace App\Http\Controllers\InformUs;

use App\Entity\Actions\GroupAction;
use App\Entity\Actions\JobAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Group\StoreGroupRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class JobController extends Controller
{

    public function __construct(private JobAction $jobAction)
    {
        $this->jobAction = $jobAction;
    }

    public function index(Request $request)
    {
        $categories = Category::query()->jobs()->active()->where('category_id', null)->with('categories')->orderBy('sort_id')->get();

        return view('inform-us.create-job', [
            'categories' => $categories,
        ]);
    }

    public function store(StoreGroupRequest $request)
    {
        $job = $this->jobAction->store($request, null, false);

        return redirect()->route('inform-us.job')->with('success', "Спасибо, что делитесь полезной информацией! Благодаря вам наше сообщество становится более полезным и дружным. Мы рады, что вы с нами!");
    }
}
