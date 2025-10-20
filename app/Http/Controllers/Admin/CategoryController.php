<?php

namespace App\Http\Controllers\Admin;

use App\Entity\Actions\CategoryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function __construct(private CategoryAction $categoryAction)
    {
        $this->categoryAction = $categoryAction;
    }

    public function index()
    {
        return view('admin.category.index');
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        $this->categoryAction->store($request);

        return redirect()->route('admin.category.index')->with('success', 'Категория добавлена');
    }

    public function edit(Category $category)
    {
        return view('admin.category.edit', ['category' => $category]);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category = $this->categoryAction->update($request, $category);

        return redirect()->route('admin.category.edit', ['category' => $category->id])
            ->with('success', 'Категория сохранена');
    }


    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.category.index')->with('success', 'Категория удалена');
    }
}
