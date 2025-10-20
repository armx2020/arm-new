<div class="flex flex-row rounded-lg bg-white h-36 md:h-64 lg:h-80 p-2 sm:p-4 relative drop-shadow-sm hover:drop-shadow-md"
    id="{{ $entity->id }}_card">
    <a href="{{ route($entityShowRoute, ['idOrTranscript' => $entity->id]) }}">
        <img class="h-32 w-32 min-h-32 min-w-32 md:h-56 md:w-56 md:min-h-56 md:min-w-56 lg:h-72 lg:w-72 lg:min-h-72 lg:min-w-72 rounded-lg object-cover"
            @if (isset($entity->images[0])) src={{ asset('storage/' . $entity->images[0]->path) }}
        @else src={{ url('/image/no_photo.jpg') }} @endif
            alt="{{ $entity->name }}" />
    </a>
    <div class="px-3 lg:px-5 flex flex-col flex-1">
        <div class="flex max-h-8 overflow-hidden mb-2">
            <a href="{{ route($entityShowRoute, ['idOrTranscript' => $entity->id]) }}">
                <p
                    class="text-xs md:text-base lg:text-xl font-bold leading-tight text-neutral-700 break-words text-ellipsis overflow-hidden">
                    {{ $entity->name }}
                </p>
            </a>
        </div>

        @if ($entity->entity_type_id == 1)
            <div class="hidden lg:flex max-h-12 md:max-h-[4.5rem] overflow-hidden mb-2 max-w-[6.5rem] sm:max-w-[26rem]">
                <p class="text-xs md:text-base font-normal text-gray-500 break-words">
                    {{ $entity->description }}
                </p>
            </div>
        @else
            <div class="max-h-16 md:max-h-36 lg:max-h-48 overflow-hidden max-w-[6.5rem] sm:max-w-none">
                <p class="text-xs md:text-base font-normal text-gray-500 break-word">
                    {{ $entity->description }}
                </p>
            </div>
        @endif

        @if ($entity->entity_type_id == 1)
            <div class="max-h-16 md:max-h-36 lg:max-h-48 overflow-hidden">
                @php
                    $count = 0;
                @endphp

                <ul class="list-disc text-base font-normal text-gray-500 break-word ml-4">
                    @if ($entity->fields)
                        @foreach ($entity->fields as $field)
                            <li class="text-xs lg:text-base">
                                {{ $field->name }}
                            </li>

                            @php
                                $count++;

                                if ($loop->iteration == 3) {
                                    break;
                                }
                            @endphp
                        @endforeach
                    @endif

                    @if ($count <= 3 && $entity->offers)
                        @foreach ($entity->offers as $offer)
                            <li class="text-xs lg:text-base">
                                {{ $offer->name }}
                            </li>

                            @php
                                $count++;

                                if ($count == 3) {
                                    break;
                                }
                            @endphp
                        @endforeach
                    @endif
                </ul>
            </div>
        @endif


        {{-- Ссылка на источник --}}
        @if ($entity->category_id == 19 && isset($entity->link))
            <div class="flex mt-1 lg:mt-4">
                <a href="{{ $entity->link }}"
                    class="whitespace-nowrap text-[clamp(10px, 4vw, 16px)] cursor-pointer inline-block bg-blue-700 hover:bg-blue-800 rounded-md px-2 lg:px-6 pb-1 lg:pb-2 pt-1 lg:pt-2.5 text-center text-white text-xs lg:text-base">
                    Записаться на приём
                </a>
            </div>
        @endif

        @if ($entity->city_id && $entity->city_id !== 1)
            <div class="max-h-4 mb-0 md:max-h-36 lg:max-h-48  break-words overflow-hidden">
                <p
                    class="mt-3 break-words text-xs font-medium text-blue-500 absolute top-[6.5rem] sm:top-[13.5rem] block lg:hidden">
                    {{ strtok($entity->city->name, ' ') }}
                </p>
            </div>
        @endif

        <div class="hidden lg:block absolute top-24 sm:top-[17.5rem]">
            <x-pages.social :entity=$entity />
        </div>

    </div>

    @if (isset($entity->phone) || $entity->city_id !== 1)
        <div class="hidden xl:flex flex-initial text-right flex flex-col w-44 text-wrap whitespace-normal">
            <p class="mb-1 font-medium">
                @if ($entity->phone && $entity->phone != '')
                    @php
                        $phoneFull = trim($entity->phone);
                        $VISIBLE_COUNT = 8;

                        if (mb_strlen($phoneFull) <= $VISIBLE_COUNT) {
                            $phoneMasked = $phoneFull;
                        } else {
                            $phoneMasked = mb_substr($phoneFull, 0, $VISIBLE_COUNT, 'UTF-8');
                        }
                    @endphp
                    <div class="flex items-center justify-end gap-1">
                        <a href="tel:{{ $phoneFull }}" data-phone="{{ $phoneFull }}"
                            class="full-phone text-blue-600 whitespace-nowrap font-medium">
                            {{ $phoneMasked }}
                        </a>

                        <button type="button"
                            class="show-phone font-medium text-blue-600 hover:underline text-sm whitespace-nowrap">
                            Показать
                        </button>
                    </div>
                @endif

                @if ($entity->city_id && $entity->city_id !== 1)
                    <p class="break-words font-medium text-blue-500 hidden lg:block">
                        {{ $entity->city->name }}
                    </p>
                @endif
            </p>
        </div>
    @endif
</div>

<script type='text/javascript'>
    $(document).ready(function() {
        $(document).on('click', '.show-phone', function(e) {
            e.preventDefault();
            const $link = $(this).siblings('.full-phone');
            const phoneFull = $link.data('phone');
            $link.text(phoneFull);
            $(this).remove();
        });

        $('#{!! $entity->id !!}_card').on('click', function() {
            const mobileWidthMediaQuery = window.matchMedia('(max-width: 768px)')

            if (mobileWidthMediaQuery.matches) {
                window.location.href = "{!! route($entityShowRoute, ['idOrTranscript' => $entity->id]) !!}";
            }
        });

    });
</script>
