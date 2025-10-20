@if ($entities->isEmpty())
    <div class="flex flex-row mx-1">
        <div
            class="mb-4 basis-full rounded-lg bg-blue-100 px-6 py-3 text-base text-blue-700 min-h-[2rem] hover:text-blue-500">
            <a href="{{ route($entitiesName . '.create') }}" class="">
                Здесть пока пусто, <span class="underline">нажмите сюда</span>, чтобы добавить
            </a>
        </div>
    </div>
@else
    <div class="grid grid-cols-1 gap-3 lg:gap-5">
        @foreach ($entities as $entity)
            <x-profile.card :$entity :$entitiesName :$entityName />
        @endforeach
    </div>

    <div class="flex flex-row my-5 mx-1">
        <div class="w-10/12">
            {{ $entities->onEachSide(2)->links() }}
        </div>
        <a href="{{ route($entitiesName . '.create') }}"
            class="flex bg-blue-500 rounded-lg px-6 pb-1 pt-1 text-white ml-auto">
            добавить
        </a>
    </div>
@endif
