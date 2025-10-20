<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class CategoryEntityController extends Controller
{
    public function index()
    {
        return view('admin.category-entity.index');
    }
}
