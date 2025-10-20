<div class="flex items-center">

    <x-dropdown align="left" width="64" outside='false'>
        <x-slot name="trigger">
            <button type="button"
                class="w-full text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium inline-flex items-center justify-center rounded-lg text-sm px-2 py-2 lg:px-3 lg:py-2 text-center sm:w-auto">
                столбцы
                <div class="ms-1 mt-1">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </button>
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 p-2 lg:p-4 gap-1">
                @foreach ($allColumns as $column)
                    <div class="flex basis-1/3">
                        <p>
                            <input name="{{ $column }}" value="{{ $column }}" type="checkbox" wire:model.live="selectedColumns">
                            {{ __('column.' . $column) }}
                        </p>
                    </div>
                @endforeach
            </div>
        </x-slot>
    </x-dropdown>

</div>
