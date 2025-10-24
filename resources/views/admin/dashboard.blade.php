@extends('admin.layouts.app')

@section('content')

    <div class="pt-6 px-4 xl:pl-10 xl:pr-0 max-w-7xl mx-auto">

        <div class="my-4 w-full grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">

            <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 ">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="text-2xl sm:text-3xl leading-none font-bold text-gray-900">{{ $countUsersAll }}</span>
                        <h3 class="text-base font-normal text-gray-500">Всего пользователей</h3>
                    </div>
                    <div class="ml-5 w-0 flex flex-col items-end justify-end flex-1 text-green-500 text-base font-bold">
                        <h3 class="text-base font-normal text-gray-500">сегодня</h3>
                        {{ $countUsersToday }}
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 ">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span
                            class="text-2xl sm:text-3xl leading-none font-bold text-gray-900">{{ $countCompaniesAll }}</span>
                        <h3 class="text-base font-normal text-gray-500">Всего компаний</h3>
                    </div>
                    <div class="ml-5 w-0 flex flex-col items-end justify-end flex-1 text-green-500 text-base font-bold">
                        <h3 class="text-base font-normal text-gray-500">сегодня</h3>
                        {{ $countCompaniesToday }}
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 ">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="text-2xl sm:text-3xl leading-none font-bold text-gray-900">{{ $countGroupsAll }}</span>
                        <h3 class="text-base font-normal text-gray-500">Всего групп</h3>
                    </div>
                    <div class="ml-5 w-0 flex flex-col items-end justify-end flex-1 text-green-500 text-base font-bold">
                        <h3 class="text-base font-normal text-gray-500">сегодня</h3>
                        {{ $countGroupsToday }}
                    </div>
                </div>
            </div>

        </div>

        <div class="w-full">
            <div class="bg-white shadow rounded-lg mb-4 p-4 sm:p-6 h-full">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold leading-none text-gray-900">Новые пользователи</h3>
                    <a href="{{ route('admin.user.index') }}"
                        class="text-sm font-medium text-cyan-600 hover:bg-gray-100 rounded-lg inline-flex items-center p-2">
                        посмотреть все
                    </a>
                </div>
                <div class="flow-root">
                    <ul role="list" class="divide-y divide-gray-200">
                        @if (count($users) === 0)
                            <li class="text-center py-3 sm:py-4">
                                нет пользователей
                            </li>
                        @else
                            @foreach ($users as $user)
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            @if ($user->image == null)
                                                <img class="h-8 w-8 rounded-full" src="{{ url('/image/no-image.png') }}"
                                                    alt="{{ $user->firstname }} avatar">
                                            @else
                                                <img class="h-8 w-8 rounded-full"
                                                    src="{{ \App\Helpers\StorageHelper::imageUrl($user->image) }}"
                                                    alt="{{ $user->firstname }} avatar">
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('admin.user.edit', ['user' => $user->id]) }}">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $user->firstname }} {{ $user->lastname }}
                                                </p>
                                                <p class="text-sm text-gray-500 truncate">
                                                    {{ $user->email }}
                                                </p>
                                            </a>
                                        </div>
                                        <div class="inline-flex items-center text-base font-semibold text-gray-900">
                                            {{ $user->city->name }}
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <div class="w-full">
            <div class="bg-white shadow rounded-lg mb-4 p-4 sm:p-6 h-full">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold leading-none text-gray-900">Новые сообщения</h3>
                    <a href="{{ route('admin.appeal.index') }}"
                        class="text-sm font-medium text-cyan-600 hover:bg-gray-100 rounded-lg inline-flex items-center p-2">
                        посмотреть все
                    </a>
                </div>
                <div class="flow-root">
                    <ul role="list" class="divide-y divide-gray-200">
                        @if (count($appeals) === 0)
                            <li class="text-center py-3 sm:py-4">
                                нет сообщений
                            </li>
                        @else
                            @foreach ($appeals as $appeal)
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('admin.appeal.edit', ['appeal' => $appeal->id]) }}">
                                                {{ $appeal->message }}
                                            </a>
                                        </div>
                                        <div class="inline-flex items-center text-base font-semibold text-gray-900">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $appeal->entity?->name }}
                                            </p>
                                            <p class="mx-1 text-sm text-gray-500 truncate">
                                                {{ $appeal->phone }}
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <div class="w-full pb-20">
            <div class="bg-white shadow rounded-lg mb-4 p-4 sm:p-6 h-full">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold leading-none text-gray-900">Новые непроверенные изображения</h3>
                    <a href="{{ route('admin.image.index') }}"
                        class="text-sm font-medium text-cyan-600 hover:bg-gray-100 rounded-lg inline-flex items-center p-2">
                        посмотреть все
                    </a>
                </div>
                <div class="flow-root">
                    <ul role="list" class="divide-y divide-gray-200">
                        @if (count($images) === 0)
                            <li class="text-center py-3 sm:py-4">
                                нет непроверенных изображений
                            </li>
                        @else
                            @foreach ($images as $image)
                                @if ($image->imageable)
                                    <li class="py-3 sm:py-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-1 min-w-0">
                                                <a
                                                    href="{{ route('admin.entity.edit', ['entity' => $image->imageable->id]) }}">
                                                    {{ $image->id }}
                                                </a>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <img class="w-8 h-8 object-cover rounded-lg"
                                                    src="{{ \App\Helpers\StorageHelper::imageUrl($image->path) }}" alt="Image">
                                            </div>

                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm text-gray-500 truncate">
                                                    {{ $image->imageable->type->name }}
                                                </p>
                                                <a
                                                    href="{{ route('admin.entity.edit', ['entity' => $image->imageable->id]) }}">
                                                    {{ $image->imageable->name }}
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>


    </div>

@endsection
