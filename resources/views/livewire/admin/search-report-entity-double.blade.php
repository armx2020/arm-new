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
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-200">
                                <tr>
                                    <th class="p-4 text-left font-medium text-gray-500 whitespace-nowrap">
                                        <button wire:click.prevent="sortBy('field')">
                                            Тип дубля
                                        </button>
                                        @if ($this->sortField === 'field')
                                            @if ($this->sortAsc)
                                                &#8593
                                            @else
                                                &#8595
                                            @endif
                                        @endif

                                    </th>
                                    <th class="p-4 text-left text-xs font-medium text-gray-500 whitespace-nowrap">
                                        <button wire:click.prevent="sortBy('db')">
                                            Дубли по базе
                                        </button>
                                        @if ($this->sortField === 'db')
                                            @if ($this->sortAsc)
                                                &#8593
                                            @else
                                                &#8595
                                            @endif
                                        @endif
                                    </th>
                                    <th class="p-4 text-left text-xs font-medium text-gray-500 whitespace-nowrap">
                                        <button wire:click.prevent="sortBy('region')">
                                            Дубли внутри региона
                                        </button>
                                        @if ($this->sortField === 'region')
                                            @if ($this->sortAsc)
                                                &#8593
                                            @else
                                                &#8595
                                            @endif
                                        @endif
                                    </th>
                                    <th class="p-4 text-left text-xs font-medium text-gray-500 whitespace-nowrap">
                                        <button wire:click.prevent="sortBy('city')">
                                            Дубли внутри города
                                        </button>
                                        @if ($this->sortField === 'city')
                                            @if ($this->sortAsc)
                                                &#8593
                                            @else
                                                &#8595
                                            @endif
                                        @endif
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($table as $row)
                                    <tr>
                                        <td class="p-4 text-xs text-gray-800 whitespace-nowrap">
                                            {{ $row['field'] }}
                                        </td>
                                        <td class="p-4 text-gray-800 whitespace-nowrap">
                                            <a href="{{ route('admin.entity.index', ['duplicatesField' => $row['double']]) }}">
                                                {{ $row['db'] }}
                                            </a>
                                        </td>
                                        <td class="p-4 text-gray-800 whitespace-nowrap">
                                            <a href="{{ route('admin.entity.index', ['duplicatesField' => $row['double'], 'doubleRegion' => 1]) }}">
                                                {{ $row['region'] }}
                                            </a>
                                        </td>
                                        <td class="p-4 text-gray-800 whitespace-nowrap">
                                            <a href="{{ route('admin.entity.index', ['duplicatesField' => $row['double'], 'doubleCity' => 1]) }}">
                                                {{ $row['city'] }}
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
