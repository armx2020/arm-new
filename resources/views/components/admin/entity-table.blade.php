<div>
    <div class="py-6 px-4 xl:pl-10 xl:pr-0 max-w-7xl mx-auto rounded-t-lg">
        <x-admin.alert />

        <div class="p-4 bg-white block shadow sm:flex items-center justify-between border-b border-gray-200">
            <div class="my-3 w-full">
                <div class="mb-4">
                    <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">
                        {{ $title }}
                    </h1>
                </div>
                <div class="flex flex-row justify-between">

                    <div class="flex space-x-1">
                        <x-admin.columns :allColumns=$allColumns />
                    </div>

                    <div class="hidden sm:flex items-center sm:divide-x sm:divide-gray-100 mb-3 sm:mb-0">
                        <form class="lg:pr-3" action="#" method="GET">
                            <label for="search" class="sr-only">Search</label>
                            <div class="mt-1 relative lg:w-64 xl:w-96">
                                <input type="text" wire:model.live="term" id="search" name="$name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                    placeholder="Поиск">
                            </div>
                        </form>
                    </div>

                    @if ($entityName !== 'appeal' && $entityName !== 'image' && Route::has('admin.' . $entityName . '.create'))
                        <div class="flex items-center space-x-1 lg:space-x-2">
                            <a href="{{ route('admin.' . $entityName . '.create') }}"
                                data-modal-toggle="add-{{ $entityName }}-modal"
                                class="w-full text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium inline-flex items-center justify-center rounded-lg text-sm px-2 py-1 lg:px-3 lg:py-2 text-center sm:w-auto">
                                <svg class="-ml-1 mr-2 h-6 w-6" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Добавить
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
        <div class="w-full bg-white">
            <x-admin.filters :filters=$filters />
        </div>

        <div wire:loading class="w-full">
            <div class="bg-white shadow p-4">
                <div class="flex items-center text-center justify-center">
                    <img class="h-5 w-5 rounded-full m-4" src="{{ url('/image/loading.gif') }}">
                    LOADING
                </div>
            </div>
        </div>
        <div wire:loading.remove>
            @if ($entities->isEmpty())
                <div class="bg-white shadow p-4">
                    <div class="flex items-center text-center">
                        <h3 class="text-xl font-normal mx-auto">{{ $emptyEntity }}</h3>
                    </div>
                </div>
            @else
                <div class="mb-4 flex flex-col">
                    <div class="overflow-x-auto">
                        <div class="align-middle inline-block min-w-full">
                            <div class="shadow overflow-hidden">
                                @php
                                    $colorMap = $colorMap ?? [];
                                @endphp
                                <table class="table-fixed min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-200">
                                        <tr>
                                            @foreach ($selectedColumns as $column)
                                                @if ($column != 'img')
                                                    <th scope="col"
                                                        class="p-4 text-left text-xs font-medium text-gray-500 uppercase max-w-[20rem] truncate">
                                                        <button wire:click.prevent='sortBy("{{ $column }}")'
                                                            role="button">
                                                            {{ __('column.' . $column) }}</button>
                                                        @if ($this->sortField == $column)
                                                            @if ($this->sortAsc)
                                                                &#8593
                                                            @else
                                                                &#8595
                                                            @endif
                                                        @endif
                                                    </th>
                                                @else
                                                    <th scope="col"
                                                        class="p-4 text-left text-xs font-medium text-gray-500 max-w-[20rem] truncate cursor-default">
                                                        {{ __('column.' . $column) }}
                                                    </th>
                                                @endif
                                            @endforeach

                                            <th scope="col" class="p-4">
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($entities as $entity)
                                            @php
                                                $duplicateClass = $colorMap[$entity->id] ?? null;

                                                if ($duplicateClass) {
                                                    $rowClass =
                                                        $duplicateClass .
                                                        ' ' .
                                                        ($entity->activity ? 'text-gray-900' : 'text-gray-600');
                                                } else {
                                                    if ($entityName == 'image') {
                                                        if ($entity->checked) {
                                                            $rowClass = 'bg-white text-gray-900';
                                                        } else {
                                                            $rowClass = 'bg-gray-300 text-gray-600';
                                                        }
                                                    } else {
                                                        if ($entity->activity) {
                                                            $rowClass = 'bg-white text-gray-900';
                                                        } else {
                                                            $rowClass = 'bg-gray-300 text-gray-600';
                                                        }
                                                    }
                                                }
                                            @endphp
                                            <tr class="hover:bg-gray-200 {{ $rowClass }}">
                                                @foreach ($selectedColumns as $column)
                                                    <td
                                                        class="p-4 pt-2 pb-2 text-base text-left break-all max-w-[20rem] truncate">
                                                        @switch($column)
                                                            @case('city_id')
                                                                {{ $entity->city->name }}
                                                            @break

                                                            @case('region_id')
                                                                {{ $entity->region->name }}
                                                            @break

                                                            @case('user_id')
                                                                {{ $entity->user ? $entity->user->firstname : '-' }}
                                                            @break

                                                            @case('category_id')
                                                                {{ $entity->category ? $entity->category->name : '-' }}
                                                            @break

                                                            @case('company_id')
                                                                {{ $entity->company ? $entity->company->name : '-' }}
                                                            @break

                                                            @case('entity_type_id')
                                                                {{ $entity->type ? $entity->type->name : '-' }}
                                                            @break

                                                            @case('img')
                                                                @if (isset($entity->primaryImage->path))
                                                                    <img class="w-8 h-8 object-cover rounded-lg"
                                                                        src="{{ \App\Helpers\StorageHelper::imageUrl($entity->primaryImage->path) }}"
                                                                        alt="Image">
                                                                @else
                                                                    -
                                                                @endif
                                                            @break

                                                            @case('parent_id')
                                                                @isset($entity->parent)
                                                                    @if (isset($entity->parent->name))
                                                                        {{ $entity->parent->name }}
                                                                    @else
                                                                        <a class="text-blue-800 hover:text-blue-600"
                                                                            href="{{ route('admin.user.edit', ['user' => $entity->parent_id]) }}">
                                                                            {{ $entity->parent->firstname }}
                                                                        </a>
                                                                    @endif
                                                                @endisset
                                                            @break

                                                            @case('imageable_type')
                                                                @isset($entity->imageable->type)
                                                                    <a class="text-blue-800 hover:text-blue-600"
                                                                        href="{{ route('admin.type.edit', ['type' => $entity->imageable->type->id]) }}">
                                                                        {{ $entity->imageable->type->name }}
                                                                    </a>
                                                                @endisset
                                                            @break

                                                            @case('imageable_id')
                                                                @isset($entity->imageable)
                                                                    <a class="text-blue-800 hover:text-blue-600"
                                                                        href="{{ route('admin.entity.edit', ['entity' => $entity->imageable->id]) }}">
                                                                        {{ $entity->imageable->name }}
                                                                    </a>
                                                                @endisset
                                                            @break

                                                            @case('path')
                                                                @if (isset($entity->path))
                                                                    <img class="w-8 h-8 object-cover rounded-lg"
                                                                        src="{{ \App\Helpers\StorageHelper::imageUrl($entity->path) }}"
                                                                        alt="Image">
                                                                @else
                                                                    -
                                                                @endif
                                                            @break

                                                            @default
                                                                @if (Route::has('admin.' . $entityName . '.edit'))
                                                                    <a class="text-blue-800 hover:text-blue-600"
                                                                        href="{{ route('admin.' . $entityName . '.edit', [$entityName => $entity->id]) }}">
                                                                        {{ $entity->$column ?? '-' }}
                                                                    </a>
                                                                @else
                                                                    {{ $entity->$column ?? '-' }}
                                                                @endif
                                                        @endswitch

                                                    </td>
                                                @endforeach

                                                <td class="text-nowrap px-2 py-2 flex">

                                                    <x-dropdown align="top" width="48">
                                                        <x-slot name="trigger">
                                                            <button
                                                                class="inline-flex items-center px-2 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">

                                                                <div class="ms-1">
                                                                    <svg class="w-5 h-5" aria-hidden="true"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        fill="currentColor" viewBox="0 0 4 15">
                                                                        <path
                                                                            d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z">
                                                                        </path>
                                                                    </svg>
                                                                </div>
                                                            </button>
                                                        </x-slot>
                                                        <x-slot name="content">

                                                            @if ($entity->getTable() == 'users' && Auth::user() && Auth::user()->hasRole('super-admin'))
                                                                <div
                                                                    class="block px-4 text-sm font-medium hover:bg-gray-100 cursor-pointer">
                                                                    <button
                                                                        class="w-full h-full py-2 flex items-center space-x-2"
                                                                        wire:click.prevent='changeRole("{{ $entity->id }}")'
                                                                        role="button">
                                                                        @if ($entity->hasRole('moderator'))
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                class="w-4 h-4 fill-green-500"
                                                                                viewBox="0 0 576 512">
                                                                                <path
                                                                                    d="M192 64C86 64 0 150 0 256S86 448 192 448l192 0c106 0 192-86 192-192s-86-192-192-192L192 64zm192 96a96 96 0 1 1 0 192 96 96 0 1 1 0-192z" />
                                                                            </svg>
                                                                            <span class="text-green-500">отозвать
                                                                                права</span>
                                                                        @else
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                class="w-4 h-4 fill-gray-500"
                                                                                viewBox="0 0 576 512">
                                                                                <path
                                                                                    d="M384 128c70.7 0 128 57.3 128 128s-57.3 128-128 128l-192 0c-70.7 0-128-57.3-128-128s57.3-128 128-128l192 0zM576 256c0-106-86-192-192-192L192 64C86 64 0 150 0 256S86 448 192 448l192 0c106 0 192-86 192-192zM192 352a96 96 0 1 0 0-192 96 96 0 1 0 0 192z" />
                                                                            </svg>
                                                                            <span class="text-gray-500">дать
                                                                                права</span>
                                                                        @endif
                                                                    </button>
                                                                </div>
                                                            @endif

                                                            <div
                                                                class="block px-4 text-sm font-medium hover:bg-gray-100 cursor-pointer">
                                                                <button
                                                                    class="w-full h-full py-2 flex items-center space-x-2"
                                                                    wire:click.prevent='changeActivity("{{ $entity->id }}")'
                                                                    role="button">
                                                                    @if ($entity->activity)
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="w-4 h-4 fill-red-500"
                                                                            viewBox="0 0 576 512">
                                                                            <path
                                                                                d="M192 64C86 64 0 150 0 256S86 448 192 448l192 0c106 0 192-86 192-192s-86-192-192-192L192 64zm192 96a96 96 0 1 1 0 192 96 96 0 1 1 0-192z" />
                                                                        </svg>
                                                                        <span
                                                                            class="text-red-500">деактивировать</span>
                                                                    @else
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="w-4 h-4 fill-green-500"
                                                                            viewBox="0 0 576 512">
                                                                            <path
                                                                                d="M384 128c70.7 0 128 57.3 128 128s-57.3 128-128 128l-192 0c-70.7 0-128-57.3-128-128s57.3-128 128-128l192 0zM576 256c0-106-86-192-192-192L192 64C86 64 0 150 0 256S86 448 192 448l192 0c106 0 192-86 192-192zM192 352a96 96 0 1 0 0-192 96 96 0 1 0 0 192z" />
                                                                        </svg>
                                                                        <span
                                                                            class="text-green-500">активировать</span>
                                                                    @endif
                                                                </button>
                                                            </div>

                                                            <form
                                                                action="{{ route('admin.' . $entityName . '.destroy', [$entityName => $entity->id]) }}"
                                                                method="post"
                                                                class="block px-4 text-sm font-medium text-red-500 hover:bg-gray-100 cursor-pointer">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="w-full h-full py-2 flex items-center space-x-2">
                                                                    <svg class="w-4 h-4 fill-red-500"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 20 20" aria-hidden="true">
                                                                        <path fill-rule="evenodd"
                                                                            d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                                                            clip-rule="evenodd"></path>
                                                                    </svg>
                                                                    <span class="text-red-500">Удалить</span>
                                                                </button>
                                                            </form>
                                                        </x-slot>
                                                    </x-dropdown>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    {{ $entities->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
