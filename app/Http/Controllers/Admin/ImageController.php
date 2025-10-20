<?php

namespace App\Http\Controllers\Admin;

use App\Entity\Actions\EntityAction;
use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Scopes\CheckedScope;
use App\Models\Scopes\SortAscScope;

class ImageController extends Controller
{
    public function index()
    {
        return view('admin.image.index');
    }

    public function edit()
    {
        return view('admin.image.index');
    }

 
    public function destroy(Image $image)
    {
        $entity = Image::withOutGlobalScopes([SortAscScope::class, CheckedScope::class])->find($image->id);

        $entity->delete();

        return redirect()->back()->with('success', 'Изображение удалено');
    }


}
