<?php

namespace App\Http\Controllers\Admin;

use App\Entity\Actions\AppealAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Appeal\UpdateAppealRequest;
use App\Models\Appeal;

class AppealController extends Controller
{
    public function __construct(private AppealAction $appealAction)
    {
        $this->appealAction = $appealAction;
    }

    public function index()
    {
        return view('admin.appeal.index');
    }

    public function edit(Appeal $appeal)
    {
        return view('admin.appeal.edit', ['appeal' => $appeal]);
    }

    public function update(UpdateAppealRequest $request, Appeal $appeal)
    {
        $appeal = $this->appealAction->update($request, $appeal);

        return redirect()->route('admin.appeal.edit', ['appeal' => $appeal->id])
            ->with('success', 'Сообщение сохранена');
    }

    public function destroy(Appeal $appeal)
    {
        $appeal->delete();

        return redirect()->route('admin.appeal.index')->with('success', 'Сообщение удалена');
    }
}
