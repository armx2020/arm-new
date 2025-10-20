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
                        <x-admin.filters :filters=$filters />
                    </div>

                </div>
            </div>
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
                                <table class="table-fixed min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-200">
                                        <tr>
                                            @foreach ($selectedColumns as $column)
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
                                            @endforeach

                                            <th scope="col" class="p-4">
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($entities as $entity)
                                            <tr class="bg-white text-gray-900 hover:bg-gray-200">
                                                @foreach ($selectedColumns as $column)
                                                    <td
                                                        class="p-4 text-base text-left break-all max-w-[20rem] truncate">
                                                        @switch($column)
                                                            @case('category_id')
                                                                @if ($entity->category)
                                                                    <a class="text-blue-800 hover:text-blue-600"
                                                                        href="{{ route('admin.category.edit', ['category' => $entity->category_id]) }}">
                                                                        {{ $entity->category->name }}
                                                                    </a>
                                                                @else
                                                                    {{ $entity->category_id }}
                                                                @endif
                                                            @break

                                                            @case('main_category_id')
                                                                @if ($entity->main_category_id)
                                                                    <a class="text-blue-800 hover:text-blue-600"
                                                                        href="{{ route('admin.category.edit', ['category' => $entity->main_category_id]) }}">
                                                                        {{ $entity->mainCategory->name }}
                                                                    </a>
                                                                @else
                                                                    {{ $entity->main_category_id }}
                                                                @endif
                                                            @break

                                                            @case('entity_id')
                                                                @if ($entity->category)
                                                                    <a class="text-blue-800 hover:text-blue-600"
                                                                        href="{{ route('admin.entity.edit', ['entity' => $entity->entity_id]) }}">
                                                                        {{ $entity->entity->name }}
                                                                    </a>
                                                                @else
                                                                    {{ $entity->entity_id }}
                                                                @endif
                                                            @break

                                                            @default
                                                                {{ $entity->$column ?? '-' }}
                                                        @endswitch

                                                    </td>
                                                @endforeach

                                                <td class="text-nowrap px-2 py-2 flex">
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
