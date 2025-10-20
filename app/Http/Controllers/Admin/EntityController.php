<?php

namespace App\Http\Controllers\Admin;

use App\Entity\Actions\EntityAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Entity\StoreEntityRequest;
use App\Http\Requests\Entity\UpdateEntityRequest;
use App\Models\Entity;

class EntityController extends Controller
{
    public function __construct(private EntityAction $entityAction)
    {
        $this->entityAction = $entityAction;
    }

    public function index()
    {
        return view('admin.entity.index');
    }

    public function create()
    {
        return view('admin.entity.create');
    }

    public function store(StoreEntityRequest $request)
    {
        $user_id = $request->user ?: null;

        $this->entityAction->store($request, $user_id ?: 1);

        return redirect()->route('admin.entity.index')->with('success', 'Сущность добавлена');
    }

    public function edit(Entity $entity)
    {
        return view('admin.entity.edit', ['entity' => $entity]);
    }

    public function update(UpdateEntityRequest $request, Entity $entity)
    {
        $user_id = $request->user ?: null;

        $entity = $this->entityAction->update($request, $entity, $user_id ?: 1);

        return redirect()->route('admin.entity.edit', ['entity' => $entity->id])
            ->with('success', "Сущность сохранена");
    }

    public function destroy(Entity $entity)
    {
        $entity = $this->entityAction->destroy($entity);

        return redirect()->route('admin.entity.index')->with('success', 'Сущность удалена');
    }

    public function report()
    {
        return view('admin.entity.report.index');
    }

    public function reportTwo()
    {
        return view('admin.entity.report.index-two');
    }

    public function reportDouble()
    {
        return view('admin.entity.report.index-double');
    }
}
