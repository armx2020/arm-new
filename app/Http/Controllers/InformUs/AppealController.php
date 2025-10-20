<?php

namespace App\Http\Controllers\InformUs;

use App\Entity\Actions\AppealAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Appeal\StoreAppealRequest;
use Illuminate\Http\Request;

class AppealController extends Controller
{

    public function __construct(private AppealAction $appealAction)
    {
        $this->appealAction = $appealAction;
    }

    public function index(Request $request)
    {
        return view('inform-us.appeal');
    }

    public function store(StoreAppealRequest $request)
    {
        $appeal = $this->appealAction->store($request);

        return redirect()->route('inform-us.appeal')->with('success', "Спасибо за ваш вклад в наше сообщество! Ваша информация поможет многим найти надежные компании и услуги. Мы ценим вашу активность и заботу о наших земляках!");
    }
}
