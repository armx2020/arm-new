<?php

namespace App\Http\Controllers\Admin;

use App\Entity\Actions\UserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreUserRequest;
use App\Http\Requests\Admin\User\UpdateUserPasswordRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct(private UserAction $userAction)
    {
        $this->userAction = $userAction;
    }

    public function index()
    {
        return view('admin.user.index');
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(StoreUserRequest $request)
    {
        $this->userAction->store($request);

        return redirect()->route('admin.user.index')->with('success', 'Пользователь добавлен');
    }

    public function edit(User $user)
    {
        $entities = $user->entities()->with('primaryImage', 'city', 'region', 'category', 'type')->paginate(20);
        $entityName  = 'entity';
        $emptyEntity = 'сущностей нет';
        $selectedColumns = [
            'id',
            'img',
            'name',
            'type',
            'category_id',
            'city_id',
            'sort_id',
            'region_id',
            'address',
            'phone'
        ];

        return view('admin.user.edit', [
            'user' => $user,
            'entities' => $entities,
            'selectedColumns' => $selectedColumns,
            'entityName' => $entityName,
            'emptyEntity' => $emptyEntity
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user = $this->userAction->update($request, $user);

        return redirect()->route('admin.user.edit', ['user' => $user->id])
            ->with('success', "Пользователь обнавлён");
    }

    public function updateUserPassword(UpdateUserPasswordRequest $request, User $user)
    {
        $user->password = Hash::make($request->password);

        return redirect()->route('admin.user.edit', ['user' => $user->id])
            ->with('success', "Пароль пользователя обнавлён");
    }

    public function destroy(string $id)
    {
        $user = User::with(
            'entities',
        )->find($id);

        if (empty($user)) {
            return redirect()->route('admin.user.index')->with('alert', 'Пользователь не найден');
        }

        foreach ($user->entities as $entity) {
            if ($entity->image) {
                Storage::delete('public/' . $entity->image);
            }
            $entity->delete();
        }

        if ($user->image !== null) {
            Storage::delete('public/' . $user->image);
        }

        $user->delete();

        return redirect()->route('admin.user.index')->with('success', 'Пользователь удалён');
    }
}
