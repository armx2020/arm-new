<div class="grid grid-cols-1 gap-3 lg:gap-5">

    @php
        $isNeedRecomendation = true;
    @endphp

    @foreach ($entities as $entity)
        @if ($isNeedRecomendation && $entity->region->transcription !== $region && $region !== 'russia')
            @if ($loop->iteration == 1)
                <x-pages.absence-recomendation />
            @else
                <div class="mt-8">
                    <x-pages.absence-recomendation />
                </div>
            @endif

            @php
                $isNeedRecomendation = false;
            @endphp
        @endif
        <x-pages.card :entity="$entity" :$entityShowRoute />
    @endforeach
</div>
