<div class="flex flex-col sm:flex-row rounded-lg bg-white h-auto sm:h-64 p-4  relative">
    <a href="{{ route($entitiesName . '.show', [$entityName => $entity->id]) }}" class="sm:basis-1/4 flex-none">
        <img class="h-full w-full rounded-2xl flex object-cover rounded-2xl flex object-cover"
            src={{ isset($entity->primaryImage) ? asset('storage/' . $entity->primaryImage->path) : url('/image/no_photo.jpg') }}
            alt="{{ $entity->name }}" />
    </a>
    <div class="px-3 flex flex-col sm:basis-full">
        <div class="flex max-h-18">
            <a href="{{ route($entitiesName . '.show', [$entityName => $entity->id]) }}">
                <p
                    class="mb-2 mt-2 sm:mt-0 break-words text-md font-semibold leading-tight text-neutral-700 text-ellipsis overflow-hidden">
                    {{ mb_substr($entity->name, 0, 130, 'UTF-8') }}
                    @if (mb_strlen($entity->name) > 130)
                        ...
                    @endif
                </p>
            </a>
        </div>

        @isset($entity->description)
            <p class="mb-2 break-words text-xs font-medium text-neutral-600">
                {{ mb_substr($entity->description, 0, 350, 'UTF-8') }}
                @if (mb_strlen($entity->description) > 350)
                    ...
                @endif
            </p>
        @endisset

    </div>
    <div class="sm:basis-1/4 flex-initial text-right flex flex-col">
        <div class="absolute top-[0.6rem] right-[0.6rem]">
            <a href="{{ route($entitiesName . '.edit', [$entityName => $entity->id]) }}"
                class="inline rounded-md p-1 my-1" title="редактировать">
                <svg class="inline" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    version="1.1" id="Capa_1" x="0px" y="0px" width="16" height="16"
                    viewBox="0 0 485.219 485.22" style="enable-background:new 0 0 485.219 485.22;" xml:space="preserve">
                    <g>
                        <path
                            d="M467.476,146.438l-21.445,21.455L317.35,39.23l21.445-21.457c23.689-23.692,62.104-23.692,85.795,0l42.886,42.897   C491.133,84.349,491.133,122.748,467.476,146.438z M167.233,403.748c-5.922,5.922-5.922,15.513,0,21.436   c5.925,5.955,15.521,5.955,21.443,0L424.59,189.335l-21.469-21.457L167.233,403.748z M60,296.54c-5.925,5.927-5.925,15.514,0,21.44   c5.922,5.923,15.518,5.923,21.443,0L317.35,82.113L295.914,60.67L60,296.54z M338.767,103.54L102.881,339.421   c-11.845,11.822-11.815,31.041,0,42.886c11.85,11.846,31.038,11.901,42.914-0.032l235.886-235.837L338.767,103.54z    M145.734,446.572c-7.253-7.262-10.749-16.465-12.05-25.948c-3.083,0.476-6.188,0.919-9.36,0.919   c-16.202,0-31.419-6.333-42.881-17.795c-11.462-11.491-17.77-26.687-17.77-42.887c0-2.954,0.443-5.833,0.859-8.703   c-9.803-1.335-18.864-5.629-25.972-12.737c-0.682-0.677-0.917-1.596-1.538-2.338L0,485.216l147.748-36.986   C147.097,447.637,146.36,447.193,145.734,446.572z" />
                    </g>
                </svg>
            </a>
        </div>
    </div>
</div>
