<div class="flex items-center p-2">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 w-full p-2">
        @foreach ($filters as $name => $type)
            <div>
                @switch($type)
                    {{-- Фильтр по датам: две даты "От" / "До" --}}
                    @case('date')
                    <div class="mb-1 font-semibold">
                        {{ __('column.' . $name) }}
                    </div>
                    <div class="flex gap-2">
                        <input
                            type="date"
                            wire:model.live="selectedFilters.{{ $name }}.>="
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg
                                   focus:ring-cyan-600 focus:border-cyan-600 p-2.5 w-full"
                        >
                        <input
                            type="date"
                            wire:model.live="selectedFilters.{{ $name }}.<="
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg
                                   focus:ring-cyan-600 focus:border-cyan-600 p-2.5 w-full"
                        >
                    </div>
                    @break

                    {{-- Фильтр bool (да/нет) --}}
                    @case('bool')
                    <div class="mb-1 font-semibold">
                        {{ __('column.' . $name) }}
                    </div>
                    <select
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg
                               focus:ring-cyan-600 focus:border-cyan-600 p-2.5 w-full"
                        wire:model.live="selectedFilters.{{ $name }}.="
                    >
                        <option value="">- все -</option>
                        <option value="1">да</option>
                        <option value="0">нет</option>
                    </select>
                    @break

                    {{-- Фильтр select --}}
                    @case('select')

                    {{-- region_id --}}
                    @if ($name == 'region_id')
                        <div class="mb-1 font-semibold">
                            {{ __('column.' . $name) }}
                        </div>
                        <select
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg
                                   focus:ring-cyan-600 focus:border-cyan-600 p-2.5 w-full"
                            wire:model.live="selectedFilters.{{ $name }}.="
                        >
                            <option value=""> - все регионы -</option>
                            @foreach (\App\Models\Region::all() as $region)
                                <option value="{{ $region->id }}">{{ $region->name }}</option>
                            @endforeach
                        </select>
                    @endif

                    {{-- double --}}
                    @if ($name == 'double')
                        <div class="mb-1 font-semibold">
                            {{ __('column.' . $name) }}
                        </div>
                        <select
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg
                                   focus:ring-cyan-600 focus:border-cyan-600 p-2.5 w-full"
                            wire:model.live="selectedFilters.{{ $name }}.="
                        >
                            <option value=""> - Все -</option>
                            @foreach (\App\Models\Region::all() as $region)
                                <option value="{{ $region->id }}">{{ $region->name }}</option>
                            @endforeach
                        </select>
                    @endif

                    {{-- region_top / city_top --}}
                    @if ($name == 'region_top' || $name == 'city_top')
                        <div class="mb-1 font-semibold">
                            {{ __('column.' . $name) }}
                        </div>
                        <select
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg
                                   focus:ring-cyan-600 focus:border-cyan-600 p-2.5 w-full"
                            wire:model.live="selectedFilters.{{ $name }}.="
                        >
                            <option value=""> - Все -</option>
                            <option value="1">1 место</option>
                            <option value="2">2 место</option>
                            <option value="3">3 место</option>
                        </select>
                    @endif

                    {{-- city_id --}}
                    @if ($name == 'city_id')
                        <div class="mb-1 font-semibold">
                            {{ __('column.' . $name) }}
                        </div>
                        <select
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg
                                   focus:ring-cyan-600 focus:border-cyan-600 p-2.5 w-full"
                            wire:model.live="selectedFilters.{{ $name }}.="
                        >
                            <option value=""> - все города - </option>
                            @foreach (\App\Models\City::orderBy('name')->get() as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                    @endif

                    {{-- category_id --}}
                    @if ($name == 'category_id')
                        <div class="mb-1 font-semibold">
                            {{ __('column.' . $name) }}
                        </div>
                        <select
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg
                                   focus:ring-cyan-600 focus:border-cyan-600 p-2.5 w-full"
                            wire:model.live="selectedFilters.{{ $name }}.="
                        >
                            <option value=""> - все категории - </option>
                            @foreach (\App\Models\Category::orderBy('name')->main()->get() as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    @endif

                    {{-- field_id --}}
                    @if ($name == 'field')
                        <div class="mb-1 font-semibold">
                            {{ __('column.' . $name) }}
                        </div>
                        <select
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg
                                   focus:ring-cyan-600 focus:border-cyan-600 p-2.5 w-full"
                            wire:model.live="selectedFilters.{{ $name }}.="
                        >
                            <option value=""> - все направления - </option>
                            @foreach (\App\Models\Category::orderBy('name')->whereNotNull('category_id')->get() as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    @endif

                    {{-- type --}}
                    @if ($name == 'type')
                        <div class="mb-1 font-semibold">
                            {{ __('column.' . $name) }}
                        </div>
                        <select
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg
                                   focus:ring-cyan-600 focus:border-cyan-600 p-2.5 w-full"
                            wire:model.live="selectedFilters.{{ $name }}.="
                        >
                            <option value="">Все</option>
                            <option value="vacancy">Вакансии</option>
                            <option value="resume">Резюмэ</option>
                        </select>
                    @endif

                    {{-- entity_type_id --}}
                    @if ($name == 'entity_type_id')
                        <div class="mb-1 font-semibold">
                            {{ __('column.' . $name) }}
                        </div>
                        <select
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg
                                   focus:ring-cyan-600 focus:border-cyan-600 p-2.5 w-full"
                            wire:model.live="selectedFilters.{{ $name }}.="
                        >
                            <option value="">- выберите тип -</option>
                            @foreach (\App\Models\EntityType::all() as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    @endif
                    @break

                    {{-- Фильтр relation --}}
                    @case('relation')
                    {{-- group_id для telegram_messages --}}
                    @if ($name == 'group_id')
                        <div class="mb-1 font-semibold">
                            Telegram группа
                        </div>
                        <select
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg
                                   focus:ring-cyan-600 focus:border-cyan-600 p-2.5 w-full"
                            wire:model.live="selectedFilters.{{ $name }}.="
                        >
                            <option value="">- все группы -</option>
                            @if (isset($telegramGroups))
                                @foreach ($telegramGroups as $group)
                                    <option value="{{ $group->id }}">
                                        {{ $group->title }} 
                                        @if($group->username)
                                            (@{{ $group->username }})
                                        @endif
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    @endif
                    @break
                @endswitch
            </div>
        @endforeach
    </div>
</div>
