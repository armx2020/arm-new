<div class="text-2xl font-medium mb-3 px-1">Другие</div>

<div class="grid grid-cols-1 gap-3 lg:gap-5">
    @foreach ($entities as $entity)
        <x-pages.card :entity="$entity" :$entityShowRoute />
    @endforeach
</div>
