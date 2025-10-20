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
            <div class="mb-4 flex flex-col">
                <div class="overflow-x-auto">
                    <div class="align-middle inline-block min-w-full">
                        <div class="shadow overflow-hidden">
                            <table class="table-fixed min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-200">
                                <tr>
                                    <th scope="col" class="pl-4 text-left text-gray-500 font-medium whitespace-nowrap">
                                        <button wire:click.prevent='sortBy("region")' role="button">
                                            Регион
                                        </button>
                                        @if ($sortField == "region")
                                            @if ($sortAsc) &#8593; @else &#8595; @endif
                                        @endif
                                    </th>

                                    <th scope="col" class="p-4 text-left text-gray-500 text-xs font-medium whitespace-nowrap">
                                        <button wire:click.prevent='sortBy("population")' role="button">
                                            Численность
                                        </button>
                                        @if ($sortField == "population")
                                            @if ($sortAsc) &#8593; @else &#8595; @endif
                                        @endif
                                    </th>

                                    <th scope="col" class="p-4 text-left text-gray-500 text-xs font-medium whitespace-nowrap">
                                        <button wire:click.prevent='sortBy("total_entities")' role="button">
                                            Сущности
                                        </button>
                                        @if ($sortField == "total_entities")
                                            @if ($sortAsc) &#8593; @else &#8595; @endif
                                        @endif
                                    </th>

                                    @foreach ($categories as $category)
                                        <th scope="col" class="p-4 text-left text-gray-500 text-xs font-medium whitespace-nowrap">
                                            <button wire:click.prevent='sortBy("{{ $category->name }}")' role="button">
                                                {{ $category->name }}
                                            </button>
                                            @if ($sortField == $category->name)
                                                @if ($sortAsc) &#8593; @else &#8595; @endif
                                            @endif
                                        </th>
                                @endforeach

                                    <th scope="col" class="p-4 text-left text-gray-500 text-xs font-medium whitespace-nowrap">
                                        <button wire:click.prevent='sortBy("total")' role="button">
                                            Итог
                                        </button>
                                        @if ($sortField == "total")
                                            @if ($sortAsc) &#8593; @else &#8595; @endif
                                        @endif
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($table as $row)
                                    <tr class="hover:bg-gray-200 @if ($row['region']['name'] === 'Итоги') bg-gray-200 font-bold @endif">

                                        <td class="pl-4 text-xs whitespace-nowrap">
                                            @if ($row['region']['name'] !== 'Итоги')
                                                <button wire:click.prevent='sortBy("count", {{ $row["region"]["id"] }})'>
                                                    {{ $row['region']['name'] }}
                                                </button>
                                                @if ($sortRegionId === $row["region"]["id"])
                                                    @if ($sortAsc) &#8593; @else &#8595; @endif
                                                @endif
                                            @else
                                                {{ $row['region']['name'] }}
                                            @endif
                                        </td>

                                        <td class="p-4 text-base text-left whitespace-nowrap">
                                            {{ number_format($row['population'], 0, '.', ' ') }}
                                        </td>

                                        <td class="p-4 text-base text-left whitespace-nowrap">
                                            <a href="{{ route('admin.entity.index', [
                                                    'type' => $this->selectedFilters['entity_type_id']['='] ?? null,
                                                    'region' => $row['region']['id']
                                                ]) }}">
                                                {{ number_format($row['total_entities'], 0, '.', ' ') }}
                                            </a>
                                        </td>

                                        @foreach ($categories as $category)
                                            <td class="p-4 text-base text-left whitespace-nowrap">
                                                <a href="{{ route('admin.entity.index', [
                                                        'type' => $this->selectedFilters['entity_type_id']['='] ?? null,
                                                        'region' => $row['region']['id'],
                                                        'category' => $category->id
                                                    ]) }}">
                                                    {{ number_format($row[$category->name] ?? 0, 0, '.', ' ') }}
                                                </a>
                                            </td>
                                    @endforeach

                                        <td class="p-4 font-bold text-base whitespace-nowrap">
                                            <a href="{{ route('admin.entity.index', [
                                                    'type' => $this->selectedFilters['entity_type_id']['='] ?? null,
                                                    'region' => $row['region']['id']
                                                ]) }}">
                                                {{ number_format($row['total'], 0, '.', ' ') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
