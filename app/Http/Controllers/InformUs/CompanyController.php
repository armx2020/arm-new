<?php

namespace App\Http\Controllers\InformUs;

use App\Entity\Actions\CompanyAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\StoreCompanyRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CompanyController extends Controller
{

    public function __construct(private CompanyAction $companyAction)
    {
        $this->companyAction = $companyAction;
    }

    public function index(Request $request)
    {
        $categories = Category::query()->companies()->active()->where('category_id', null)->with('categories')->orderBy('sort_id')->get();

        return view('inform-us.create-company', [
            'categories' => $categories,
        ]);
    }

    public function store(StoreCompanyRequest $request)
    {
        $fields = $request->fields;
        $company = $this->companyAction->store($request, null, false);

        return redirect()->route('inform-us.company')->with('success', "Спасибо, что делитесь полезной информацией! Благодаря вам наше сообщество становится более полезным и дружным. Мы рады, что вы с нами!");
    }
}
